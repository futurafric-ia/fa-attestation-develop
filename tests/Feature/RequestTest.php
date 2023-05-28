<?php

use Domain\Authorization\Models\Role;
use Domain\User\Models\User;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->seed();
    $this->user = User::factory()->create();

    $this->broker = \Domain\Broker\Models\Broker::factory(['department_id' => 1])->create();
    $this->brokerUser = User::factory()->create(['type' => User::TYPE_BROKER]);
    $this->brokerUser->assignRole(Role::BROKER);
    $this->broker->addUser($this->brokerUser);
    $this->broker->setOwner($this->brokerUser);
});

test('asserts inquiries listing page exists', function () {
    $this->user->givePermissionTo('request.list');

    expect(Route::has('request.index'))->toBe(true);
    $this->actingAs($this->user)->get(route('request.index'))->assertOk();
});

it('should forbid a non-authorized user to show the inquiries', function () {
    $this->user->assignRole(Role::create(['name' => 'Dummy role']));

    $this->actingAs($this->user)->get(route('request.index'))->assertForbidden();
});

it('should show a list of requests', function () {
    $this->user->givePermissionTo('request.list');

    $response = $this->actingAs($this->user)->get(route('request.index'));
    $response->assertSee('Demandes');
    $response->assertSeeLivewire('request.requests-table');
});

test('asserts make inquiry form page exists', function () {
    $this->user->givePermissionTo(['request.create', 'request.list']);

    expect(Route::has('request.create'))->toBe(true);
    $this->actingAs($this->user)->get(route('request.create'))->assertOk();
});

it('should forbid a non-authorized user to access the request form page', function () {
    $this->user->assignRole(Role::create(['name' => 'Dummy role']));

    $this->actingAs($this->user)->get(route('request.create'))->assertForbidden();
});

it('should forbid a non-authorized user to make an inquiry', function () {
    $this->actingAs($this->user);

    livewire(\App\Http\Livewire\Request\CreateRequestForm::class)
        ->set('state', [])
        ->call('saveRequest')
        ->assertForbidden();
});

it('should allow an authorized user to make an inquiry', function () {
    $this->actingAs($this->brokerUser);

    livewire(\App\Http\Livewire\Request\CreateRequestForm::class)
        ->set('state', [
            'quantity' => 1,
            'notes' => null,
            'attestation_type_id' => \Domain\Attestation\Models\AttestationType::GREEN,
            'expected_at' => null,
        ])
        ->call('saveRequest');

    \Pest\Laravel\assertDatabaseHas('requests', [
        'attestation_type_id' => \Domain\Attestation\Models\AttestationType::GREEN,
        'quantity' => 1,
    ]);
});

test('asserts quantity is required', function () {
    $this->actingAs($this->brokerUser);

    livewire(\App\Http\Livewire\Request\CreateRequestForm::class)
        ->set('state', [
            'quantity' => null,
        ])
        ->call('saveRequest')->assertHasErrors(['state.quantity' => 'required']);
});

test('asserts quantity must be at least 1', function () {
    $this->actingAs($this->brokerUser);

    livewire(\App\Http\Livewire\Request\CreateRequestForm::class)
        ->set('state', [
            'quantity' => 0,
        ])
        ->call('saveRequest')->assertHasErrors(['state.quantity' => 'min']);
});

test('asserts quantity must be an integer', function () {
    $this->actingAs($this->brokerUser);

    livewire(\App\Http\Livewire\Request\CreateRequestForm::class)
        ->set('state', [
            'quantity' => 'xxx',
        ])
        ->call('saveRequest')->assertHasErrors(['state.quantity' => 'integer']);
});


test('assert attestation type is required', function () {
    $this->actingAs($this->brokerUser);

    livewire(\App\Http\Livewire\Request\CreateRequestForm::class)
        ->set('state', [
            'attestation_type_id' => null,
        ])
        ->call('saveRequest')->assertHasErrors(['state.attestation_type_id' => 'required']);
});

test('asserts inquiry edit page exists', function () {
    $inquiry = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->brokerUser->id,
    ]);

    expect(Route::has('request.edit'))->toBe(true);
    $this->actingAs($this->brokerUser)->get(route('request.show', $inquiry))->assertOk();
});

it('should forbid a non-authorized user to access the inquiry edit page', function () {
    $this->user->assignRole(Role::create(['name' => 'Dummy role']));
    $this->broker->addUser($this->user);

    $inquiry = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->user->id,
    ]);

    $this->actingAs($this->user)->get(route('request.edit', $inquiry))->assertForbidden();
});

it('should show a form with prefilled form fields', function () {
    $this->actingAs($this->brokerUser);

    $inquiry = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->brokerUser->id,
    ]);

    livewire(\App\Http\Livewire\Request\EditRequestForm::class, ['request' => $inquiry->uuid])
        ->assertSet('state', [
            'quantity' => $inquiry->quantity,
            'expected_at' => $inquiry->expected_at->format('Y-m-d'),
            'notes' => $inquiry->notes,
            'attestation_type_id' => $inquiry->attestation_type_id,
        ]);
});

it('should update an inquiry', function () {
    $this->actingAs($this->brokerUser);

    $inquiry = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->brokerUser->id,
    ]);

    livewire(\App\Http\Livewire\Request\EditRequestForm::class, ['request' => $inquiry->uuid])
        ->assertSet('state', [
            'quantity' => $inquiry->quantity,
            'expected_at' => $inquiry->expected_at->format('Y-m-d'),
            'notes' => $inquiry->notes,
            'attestation_type_id' => $inquiry->attestation_type_id,
        ])
        ->set('state', [
            'quantity' => 2,
            'expected_at' => '2021-03-06',
            'notes' => 'xxx',
            'attestation_type_id' => \Domain\Attestation\Models\AttestationType::YELLOW,
        ])
        ->call('saveRequest');

    $inquiry->refresh();

    expect($inquiry->quantity)->toEqual(2);
    expect($inquiry->expected_at->format('Y-m-d'))->toEqual('2021-03-06');
    expect($inquiry->notes)->toEqual('xxx');
    expect($inquiry->attestation_type_id)->toEqual(\Domain\Attestation\Models\AttestationType::YELLOW);
});

test('asserts request approval form page exists', function () {
    $this->user->givePermissionTo(['request.approve', 'request.list']);

    $request = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->brokerUser,
    ]);

    expect(Route::has('request.showApprovalForm'))->toBe(true);

    $this->actingAs($this->user)->get(route('request.showApprovalForm', $request))->assertOk();
});

it('should forbid a non-authorized user to access the inquiry approval page', function () {
    $request = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->brokerUser,
    ]);

    $this->actingAs($this->user)->get(route('request.showApprovalForm', $request))->assertForbidden();
});

it('should forbid a non-authorized user to approve an request', function () {
    $this->actingAs($this->user);

    $request = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->brokerUser,
    ]);

    livewire(\App\Http\Livewire\Request\ApproveRequestForm::class, ['request' => $request])
        ->call('approveRequest');
});

it('should allow an authorized user to approve an request', function () {
    $this->user->givePermissionTo('request.approve');
    $this->actingAs($this->user);

    $request = \Domain\Request\Models\Request::factory()->create([
        'broker_id' => $this->broker->id,
        'created_by' => $this->brokerUser,
    ]);

    livewire(\App\Http\Livewire\Request\ApproveRequestForm::class, ['request' => $request])
        ->set('state', [
            'quantity_approved' => (int) $request->quantity,
            'reason' => null,
        ])
        ->call('approveRequest')
        ->assertHasNoErrors();

    $request->refresh();

    expect($request->quantity_approved)->toEqual($request->quantity);
    expect($request->state::$name)->toBe(\Domain\Request\States\Approved::$name);
});

//it('should forbid a user to approve a quantity greater that the initial quantity', function () {
//    expect(false)->toBe(true);
//});
//
//it('should forbid a user to approve an request whose status is not set to pending', function () {
//    expect(true)->toBe(false);
//});

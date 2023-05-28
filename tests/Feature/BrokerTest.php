<?php

use Domain\Authorization\Models\Role;
use Domain\Broker\Models\Broker;
use Domain\User\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('should forbid an unauthenticated to see the brokers', function () {
    $this->seed();
    $response = $this->get(route('brokers.index'));
    $response->assertRedirect(route('login'));
});

it('should forbid a non-authorized users to see the brokers', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);

    $this->actingAs($user)->get(route('brokers.index'))->assertForbidden();
});

it('should show a list of brokers', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $response = $this->actingAs($user)->get(route('brokers.index'));
    $response->assertStatus(200);
    $response->assertSee("Intermédiaires");
    $response->assertSeeLivewire('broker.brokers-table');
});

test('asserts broker creation page exists', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $response = $this->actingAs($user)->get(route('brokers.create'));
    $response->assertStatus(200);
    $response->assertSee('Créer un intermédiaire');
});

it('should forbid non-authorized users to access the broker creation page', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);

    $this->actingAs($user)->get(route('brokers.create'))->assertForbidden();
});

it('should forbid non-authorized users to create a new broker', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [])
        ->call('saveBroker')
        ->assertForbidden();
});

test('asserts code is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.code' => 'required']);
});

test('asserts email is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.email' => 'required']);
});

test('asserts email is valid', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => 'xxx',
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.email' => 'email']);
});

test('asserts name is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.name' => 'required']);
});

test('asserts department is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.department_id' => 'required']);
});

test('asserts owner name is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.owner.first_name' => 'required', 'state.owner.last_name' => 'required']);
});

test('asserts owner email is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => null,
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.owner.email' => 'required']);
});

test('asserts owner email is valid', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => 'xxx',
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertHasErrors(['state.owner.email' => 'email']);
});

test('asserts owner email can be left empty when he shares the broker credentials', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => null,
            'name' => null,
            'contact' => null,
            'address' => null,
            'department_id' => null,
            'email' => null,
            'owner' => [
                'first_name' => null,
                'last_name' => null,
                'email' => 'xxx',
                'contact' => null,
                'address' => null,
            ],
        ])
        ->set('shareCredentialsWithOwner', true)
        ->call('saveBroker')
        ->assertHasNoErrors(['state.owner.email' => 'required']);
});

it('can create a new broker', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
         ->set('state', [
             'code' => 'KGK75',
             'name' => 'Digolo',
             'contact' => null,
             'address' => null,
             'department_id' => 1,
             'email' => 'digolo@email.com',
             'owner' => [
                 'first_name' => 'Dagou',
                 'last_name' => 'Gbeu',
                 'email' => 'dagogbeu@email.com',
                 'contact' => null,
                 'address' => null,
             ],
         ])
         ->call('saveBroker');

    assertDatabaseHas('users', ['email' => 'dagogbeu@email.com']);
    assertDatabaseHas('brokers', ['email' => 'digolo@email.com']);
});

it('redirects after user creation', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\CreateBrokerForm::class)
        ->set('state', [
            'code' => 'KGK75',
            'name' => 'Digolo',
            'contact' => null,
            'address' => null,
            'department_id' => 1,
            'email' => 'digolo@email.com',
            'owner' => [
                'first_name' => 'Dagou',
                'last_name' => 'Gbeu',
                'email' => 'dagogbeu@email.com',
                'contact' => null,
                'address' => null,
            ],
        ])
        ->call('saveBroker')
        ->assertRedirect(route('brokers.index'));
});

test('asserts broker edit page exists', function () {
    $this->seed();
    $user = User::factory()->create();
    $user->assignRole(Role::ADMIN);

    expect(Route::has('brokers.edit'))->toBe(true);

    $this->actingAs($user)->get(route('brokers.edit', Broker::factory(['department_id' => 1])->create()))->assertOk();
});

it('should forbid non-authenticated user to access the broker edit page', function () {
    $this->seed();
    $response = $this->get(route('users.edit', 1));
    $response->assertRedirect(route('login'));
});

it('should forbid non-authorized users to access the broker edit page', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);

    $this->actingAs($user)->get(route('brokers.edit', Broker::factory(['department_id' => 1])->create()))->assertForbidden();
});

it('should forbid non-authorized users to edit a broker', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Broker\EditBrokerForm::class, ['slug' => Broker::factory(['department_id' => 1])->create()->slug])
        ->set('state', [])
        ->call('saveBroker')
        ->assertForbidden();
});

it('should show a broker edit form with prefilled form inputs', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    $dummyBroker = Broker::factory(['department_id' => 1])->create();

    livewire(\App\Http\Livewire\Broker\EditBrokerForm::class, ['broker' => $dummyBroker->slug])
        ->assertSet('state', [
            'code' => $dummyBroker->code,
            'name' => $dummyBroker->name,
            'contact' => $dummyBroker->contact,
            'address' => $dummyBroker->address,
            'department_id' => $dummyBroker->department_id,
            'email' => $dummyBroker->email,
        ]);
});


it('should update a user', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    $dummyBroker = Broker::factory(['department_id' => 1])->create();

    livewire(\App\Http\Livewire\Broker\EditBrokerForm::class, ['broker' => $dummyBroker->slug])
        ->assertSet('state', [
            'code' => $dummyBroker->code,
            'name' => $dummyBroker->name,
            'contact' => $dummyBroker->contact,
            'address' => $dummyBroker->address,
            'department_id' => $dummyBroker->department_id,
            'email' => $dummyBroker->email,
        ])
        ->set('state.code', 'HLH')
        ->set('state.name', 'Dummy Broker')
        ->set('state.email', 'xxx@xx.com')
        ->set('state.department_id', 1)
        ->set('state.contact', 'HLH')
        ->set('state.address', 'HLH')
        ->call('saveBroker');

    $dummyBroker->refresh();

    expect($dummyBroker->name)->toBe('Dummy Broker');
    expect($dummyBroker->code)->toBe('HLH');
    expect($dummyBroker->email)->toBe('xxx@xx.com');
    expect($dummyBroker->department_id)->toBe('1');
    expect($dummyBroker->contact)->toBe('HLH');
    expect($dummyBroker->address)->toBe('HLH');
});

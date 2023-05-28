<?php

use Domain\Authorization\Models\Role;
use Domain\Supply\Models\Supplier;
use Domain\User\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('should forbid an unauthenticated user to see the suppliers', function () {
    $this->seed();
    $response = $this->get(route('suppliers.index'));
    $response->assertRedirect(route('login'));
});

it('should forbid a non-authorized user to see the suppliers', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);

    $this->actingAs($user)->get(route('suppliers.index'))->assertForbidden();
});

it('should show a list of suppliers', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $response = $this->actingAs($user)->get(route('suppliers.index'));
    $response->assertStatus(200);
    $response->assertSee("Fournisseurs");
    $response->assertSeeLivewire('supplier.suppliers-table');
});

test('asserts supplier creation page exists', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user)->get(route('suppliers.create'))
        ->assertOk()
        ->assertSee('Créer un fournisseur');
});

it('should forbid a non-authorized user to access the supplier creation page', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);

    $this->actingAs($user)->get(route('suppliers.create'))->assertForbidden();
});

it('should forbid a non-authorized user to create a new supplier', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\CreateSupplierForm::class)
        ->set('state', [])
        ->call('saveSupplier')
        ->assertForbidden();
});

test('asserts code is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\CreateSupplierForm::class)
        ->set('state', [
            'code' => null,
        ])
        ->call('saveSupplier')
        ->assertHasErrors(['state.code' => 'required']);
});

test('asserts email is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\CreateSupplierForm::class)
        ->set('state', [
            'email' => null,
        ])
        ->call('saveSupplier')
        ->assertHasErrors(['state.email' => 'required']);
});

test('asserts email must be valid', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\CreateSupplierForm::class)
        ->set('state', [
            'email' => 'xxx',
        ])
        ->call('saveSupplier')
        ->assertHasErrors(['state.email' => 'email']);
});

test('asserts name is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\CreateSupplierForm::class)
        ->set('state', [
            'name' => null,
        ])
        ->call('saveSupplier')
        ->assertHasErrors(['state.name' => 'required']);
});

it('should create a new supplier', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\CreateSupplierForm::class)
         ->set('state', [
             'name' => 'supplierxxx',
             'code' => 'xxx',
             'email' => 'xxx@xx.com',
             'type' => null,
             'contact' => null,
             'address' => null,
         ])
         ->call('saveSupplier');

    assertDatabaseHas('suppliers', ['email' => 'xxx@xx.com']);
});

it('redirects after supplier creation', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\CreateSupplierForm::class)
        ->set('state', [
            'name' => 'supplierxxx',
            'code' => 'xxx',
            'email' => 'xxx@xx.com',
            'type' => null,
            'contact' => null,
            'address' => null,
        ])
        ->call('saveSupplier')
        ->assertRedirect(route('suppliers.index'));
});

test('asserts supplier edit page exists', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    expect(Route::has('suppliers.edit'))->toBe(true);

    $supplier = Supplier::factory()->create();

    $this->actingAs($user)->get(route('suppliers.edit', $supplier))->assertOk();
});

it('should forbid a non-authenticated user to access the supplier edit page', function () {
    $this->seed();
    $response = $this->get(route('users.edit', 1));
    $response->assertRedirect(route('login'));
});

it('should forbid a non-authorized user to access the supplier edit page', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);

    $this->actingAs($user)->get(route('suppliers.edit', supplier::factory()->create()))->assertForbidden();
});

it('should forbid a non-authorized user to edit a supplier', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::SOUSCRIPTOR);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\Supplier\EditSupplierForm::class, ['supplier' => supplier::factory()->create()->id])
        ->set('state', [])
        ->call('saveSupplier')
        ->assertForbidden();
});

it('should show a supplier edit form with prefilled form inputs', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    $dummySupplier = Supplier::factory()->create();

    livewire(\App\Http\Livewire\Supplier\EditSupplierForm::class, ['supplier' => $dummySupplier->id])
        ->assertSet('state', [
            'name' => $dummySupplier->name,
            'code' => $dummySupplier->code,
            'email' => $dummySupplier->email,
            'type' => $dummySupplier->type,
            'contact' => $dummySupplier->contact,
            'address' => $dummySupplier->address,
        ]);
});

it('should update a supplier', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    $dummySupplier = supplier::factory()->create();

    livewire(\App\Http\Livewire\Supplier\EditSupplierForm::class, ['supplier' => $dummySupplier->id])
        ->assertSet('state', [
            'code' => $dummySupplier->code,
            'name' => $dummySupplier->name,
            'contact' => $dummySupplier->contact,
            'address' => $dummySupplier->address,
            'type' => $dummySupplier->type,
            'email' => $dummySupplier->email,
        ])
        ->set('state.code', 'XDFD')
        ->set('state.name', 'Dummy supplier')
        ->set('state.email', 'xxx@xx.com')
        ->set('state.type', 'XXXX')
        ->set('state.contact', '+2548844548484')
        ->set('state.address', 'Abidjan 21')
        ->call('saveSupplier');

    $dummySupplier->refresh();

    expect($dummySupplier->name)->toBe('Dummy supplier');
    expect($dummySupplier->code)->toBe('XDFD');
    expect($dummySupplier->email)->toBe('xxx@xx.com');
    expect($dummySupplier->type)->toBe('XXXX');
    expect($dummySupplier->contact)->toBe('+2548844548484');
    expect($dummySupplier->address)->toBe('Abidjan 21');
});

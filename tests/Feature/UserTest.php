<?php

use Domain\Authorization\Models\Role;
use Domain\User\Models\User;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('requires a user to be signed in to see the users', function () {
    $this->seed();
    $response = $this->get(route('users.index'));
    $response->assertRedirect(route('login'));
});

it('requires administrator privileges to see the users', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::VALIDATOR);

    $this->actingAs($user)->get(route('users.index'))->assertForbidden();
});

it('shows a list of users', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $response = $this->actingAs($user)->get(route('users.index'));
    $response->assertStatus(200);
    $response->assertSee("Utilisateurs");
    $response->assertSeeLivewire('user.users-table');
});

it('has a user creation page', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $response = $this->actingAs($user)->get(route('users.create'));
    $response->assertStatus(200);
    $response->assertSee('Créer un utilisateur');
});

it('requires administrator privileges to access the user creation page', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::VALIDATOR);

    $this->actingAs($user)->get(route('users.create'))->assertForbidden();
});

it('requires administrator privileges to create a new user', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::VALIDATOR);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('state', [
            'identifier' => '67KGK',
            'first_name' => 'Salomon',
            'last_name' => 'Dion',
            'email' => 'fake@email.com',
            'address' => '21 BP 5137 Abidjan 21',
            'contact' => '68069493',
        ])
        ->call('saveUser')
        ->assertForbidden();
});

test('asserts identifier is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('state', [
            'identifier' => null,
            'first_name' => 'Salomon',
            'last_name' => 'Dion',
            'email' => 'fake@email.com',
            'address' => '21 BP 5137 Abidjan 21',
            'contact' => '68069493',
        ])
        ->call('saveUser')
        ->assertHasErrors(['state.identifier' => 'required']);
});

test('asserts email is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('state', [
            'identifier' => 'HLO',
            'first_name' => 'Salomon',
            'last_name' => 'Dion',
            'email' => null,
            'address' => '21 BP 5137 Abidjan 21',
            'contact' => '68069493',
        ])
        ->call('saveUser')
        ->assertHasErrors(['state.email' => 'required']);
});

test('asserts email is valid', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('state', [
            'identifier' => 'HLO',
            'first_name' => 'Salomon',
            'last_name' => 'Dion',
            'email' => 'kgjgk',
            'address' => '21 BP 5137 Abidjan 21',
            'contact' => '68069493',
        ])
        ->call('saveUser')
        ->assertHasErrors(['state.email' => 'email']);
});

test('asserts first name is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('state', [
            'first_name' => null,
            'last_name' => 'Dion',
            'email' => 'fake@email.com',
            'address' => '21 BP 5137 Abidjan 21',
            'contact' => '68069493',
        ])
        ->call('saveUser')
        ->assertHasErrors(['state.first_name' => 'required']);
});

test('asserts last name is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('state', [
            'first_name' => 'Salomon',
            'last_name' => null,
            'email' => 'fake@email.com',
            'address' => '21 BP 5137 Abidjan 21',
            'contact' => '68069493',
        ])
        ->call('saveUser')
        ->assertHasErrors(['state.last_name' => 'required']);
});

test('asserts role is required', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('roleId', null)
        ->call('saveUser')
        ->assertHasErrors(['roleId' => 'required']);
});

test('asserts department is required when role has a department', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('roleId', Role::rolesWithDeparment()->first()->id)
        ->call('saveUser')
        ->assertHasErrors(['departmentId' => 'required']);
});

test('asserts department can be left empty when role does not have a department', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('roleId', Role::SOUSCRIPTOR)
        ->call('saveUser')
        ->assertHasNoErrors(['departmentId' => 'required']);
});

it('can create a new user', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
         ->set('state', [
             'identifier' => '67KGK',
             'first_name' => 'Salomon',
             'last_name' => 'Dion',
             'email' => 'fake@email.com',
             'address' => '21 BP 5137 Abidjan 21',
             'contact' => '68069493',
         ])
        ->set('roleId', Role::VALIDATOR)
        ->set('departmentId', \Domain\Department\Models\Department::first()->id)
         ->call('saveUser');

    assertDatabaseHas('users', ['email' => 'fake@email.com']);

    $createdUser = User::firstWhere(['email' => 'fake@email.com']);

    expect($createdUser->departments()->first()->id)->toBe(\Domain\Department\Models\Department::first()->id);
    expect($createdUser->roles()->first()->id)->toBe(Role::VALIDATOR);
});

it('redirects after user creation', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);

    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\CreateUserForm::class)
        ->set('state', [
            'identifier' => '67KGK',
            'first_name' => 'Salomon',
            'last_name' => 'Dion',
            'email' => 'fake@email.com',
            'address' => '21 BP 5137 Abidjan 21',
            'contact' => '68069493',
        ])
        ->set('roleId', Role::SOUSCRIPTOR)
        ->call('saveUser')
        ->assertRedirect(route('users.index'));

    assertDatabaseHas('users', ['email' => 'fake@email.com']);
});

it('requires administrator privileges to access the user edit page', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::VALIDATOR);

    $this->actingAs($user)->get(route('users.edit', $user))->assertForbidden();
});

it('requires administrator privileges to edit user', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::VALIDATOR);
    $user->departments()->attach(1);
    $this->actingAs($user);

    livewire(\App\Http\Livewire\User\EditUserForm::class, ['user' => $user->uuid])
        ->set('state', [
            'identifier' => '67KGK',
        ])
        ->call('saveUser')
        ->assertForbidden();
});

it('shows a user edit form with prefilled form inputs', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    $dummyUser = User::factory()->create(['email' => 'dummy@email.com']);
    $dummyUser->assignRole(Role::VALIDATOR);
    $dummyUser->departments()->attach(1);

    livewire(\App\Http\Livewire\User\EditUserForm::class, ['user' => $dummyUser->uuid])
        ->assertSet('state', [
            'identifier' => $dummyUser->identifier,
            'email' => $dummyUser->email,
            'first_name' => $dummyUser->first_name,
            'last_name' => $dummyUser->last_name,
            'contact' => $dummyUser->contact,
            'address' => $dummyUser->address,
        ])
        ->assertSet('roleId', $dummyUser->roles()->first()->id)
        ->assertSet('departmentId', $dummyUser->departments()->first()->id);
});

it('can update a user', function () {
    $this->seed();
    $user = User::factory()->create(['email' => 'test@email.com']);
    $user->assignRole(Role::ADMIN);
    $this->actingAs($user);

    $dummyUser = User::factory()->create(['email' => 'dummy@email.com']);
    $dummyUser->assignRole(Role::VALIDATOR);
    $dummyUser->departments()->attach(1);

    livewire(\App\Http\Livewire\User\EditUserForm::class, ['user' => $dummyUser->uuid])
        ->assertSet('state', [
            'identifier' => $dummyUser->identifier,
            'email' => $dummyUser->email,
            'first_name' => $dummyUser->first_name,
            'last_name' => $dummyUser->last_name,
            'contact' => $dummyUser->contact,
            'address' => $dummyUser->address,
        ])
        ->assertSet('roleId', $dummyUser->roles()->first()->id)
        ->assertSet('departmentId', $dummyUser->departments()->first()->id)
        ->set('state.first_name', 'Detygon')
        ->set('roleId', Role::SOUSCRIPTOR)
        ->set('departmentId', null)
        ->call('saveUser');

    expect($dummyUser->fresh()->first_name)->toBe('Detygon');
    expect($dummyUser->fresh()->roles()->first()->id)->toBe(Role::SOUSCRIPTOR);
});

<?php

namespace App\Http\Livewire\User;

use App\ViewModels\User\UserFormViewModel;
use Domain\Authorization\Models\Role;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;

class CreateUserForm extends Component
{
    use AuthorizesRequests;

    public ?\Illuminate\Support\Collection $rolesWithDepartments = null;

    public $roleId;

    public $departmentId;

    public $state = [
        'identifier' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'address' => null,
        'contact' => null,
    ];

    public function mount()
    {
        $this->rolesWithDepartments = Role::rolesWithDeparment()->pluck('id');
    }

    public function updatedRoleId()
    {
        if ($this->departmentId && ! $this->rolesWithDepartments->contains($this->roleId)) {
            $this->departmentId = '';
        }
    }

    public function saveUser(CreateUserAction $createUserAction)
    {
        $this->authorize('create', User::class);

        $this->validate([
            'state.first_name' => ['required', 'string', 'max:255'],
            'state.last_name' => ['required', 'string', 'max:255'],
            'state.email' => ['required', 'email', Rule::unique('users', 'email')],
            'state.identifier' => ['required', 'max:255', Rule::unique('users', 'identifier')],
            'state.contact' => ['nullable', 'string', 'max:255'],
            'state.address' => ['nullable', 'string', 'max:255'],
            'roleId' => ['required', Rule::exists('roles', 'id')],
            'departmentId' => [
                new RequiredIf($this->rolesWithDepartments->contains($this->roleId)),
                'nullable',
                Rule::exists('departments', 'id'),
            ],
        ]);

        $createUserAction->execute(array_merge($this->state, [
            'roles' => [$this->roleId],
            'department_id' => $this->departmentId,
            'type' => User::TYPE_USER,
        ]));

        session()->flash('success', "L'utilisateur a été créé avec succès!");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user.create-user-form', new UserFormViewModel());
    }
}

<?php

namespace App\Http\Livewire\User;

use App\ViewModels\User\UserFormViewModel;
use Domain\Authorization\Models\Role;
use Domain\User\Actions\UpdateUserAction;
use Domain\User\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;
use Livewire\Component;

class EditUserForm extends Component
{
    use AuthorizesRequests;

    public $user;

    public $roleId;

    public $departmentId;

    public ?\Illuminate\Support\Collection $rolesWithDepartments = null;

    public $state = [
        'identifier' => null,
        'first_name' => null,
        'last_name' => null,
        'email' => null,
        'address' => null,
        'contact' => null,
    ];

    public function mount(User $user)
    {
        $this->rolesWithDepartments = Role::rolesWithDeparment()->pluck('id');
        $this->user = $user;
        $this->state = [
            'identifier' => $this->user->identifier,
            'email' => $this->user->email,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'contact' => $this->user->contact,
            'address' => $this->user->address,
        ];
        $this->roleId = $this->user->roles->first()->id;
        $this->departmentId = $this->user->roles()->first()->hasDepartment()
                ? $this->user->departments()->first()->id
                : null;
    }

    public function updatedRoleId()
    {
        if ($this->departmentId && ! $this->rolesWithDepartments->contains($this->roleId)) {
            $this->departmentId = '';
        }
    }

    public function saveUser(UpdateUserAction $updateUserAction)
    {
        $this->authorize('update', $this->user);

        $this->validate([
            'state.identifier' => ['required', Rule::unique('users', 'identifier')->ignoreModel($this->user)],
            'state.first_name' => ['required', 'string', 'max:255'],
            'state.last_name' => ['required', 'string', 'max:255'],
            'state.email' => ['required', 'email', Rule::unique('users', 'email')->ignoreModel($this->user)],
            'state.contact' => ['nullable', 'string', 'max:255'],
            'state.address' => ['nullable', 'string', 'max:255'],
            'roleId' => ['required', Rule::exists('roles', 'id')],
            'departmentId' => [
                new RequiredIf($this->rolesWithDepartments->contains($this->roleId)),
                'nullable',
                Rule::exists('departments', 'id'),
            ],
        ]);

        $updateUserAction->execute($this->user, array_merge($this->state, [
            'roles' => [$this->roleId],
            'department_id' => $this->departmentId,
        ]));

        session()->flash('success', "L'utilisateur a été mise à jour avec succès!");

        return redirect()->route('users.index');
    }

    public function render()
    {
        return view('livewire.user.edit-user-form', new UserFormViewModel($this->user));
    }
}

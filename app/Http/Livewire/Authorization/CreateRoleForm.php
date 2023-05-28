<?php

namespace App\Http\Livewire\Authorization;

use Domain\Authorization\Actions\CreateRoleAction;
use Domain\Authorization\Models\Permission;
use Domain\Authorization\Models\Role;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class CreateRoleForm extends Component
{
    use AuthorizesRequests;

    public $permissions = [];

    public $rolePermissions = [];

    public $state = [
        'name' => null,
        'description' => null,
        'has_department' => false,
        'permissions' => [],
    ];

    public function mount()
    {
        $this->permissions = Permission::isMaster()
            ->where('name', '!=', 'view_backend')
            ->with('children')
            ->get()
            ->sortByDesc(fn ($item) => $item->children->count());
    }

    public function saveRole(CreateRoleAction $createRoleAction)
    {
        $this->authorize('create', Role::class);

        $this->validate([
            'state.name' => ['required', 'string', 'min:3','unique:roles,name'],
            'state.description' => ['nullable', 'string', 'max:255'],
        ]);

        $createRoleAction->execute($this->state + ['permissions' => $this->rolePermissions]);

        session()->flash('success', 'Le rôle à été créé avec succès.');

        return redirect()->route('backend.roles.index');
    }

    public function render()
    {
        return view('livewire.authorization.create-role-form');
    }
}

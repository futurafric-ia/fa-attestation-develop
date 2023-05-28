<?php

namespace App\ViewModels\User;

use Domain\Authorization\Models\Permission;
use Domain\Authorization\Models\Role;
use Domain\Department\Models\Department;
use Domain\User\Models\User;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class UserFormViewModel extends ViewModel
{
    protected $user;

    public function __construct(User $user = null)
    {
        $this->user = $user;
    }

    public function user(): User
    {
        return $this->user ?? new User();
    }

    public function roles(): array
    {
        return Role::allowedForUser(auth()->user())->pluck('name', 'id')->toArray();
    }

    public function permissions(): Collection
    {
        return Permission::query()->get();
    }

    public function departments()
    {
        return Department::query()->pluck('name', 'id')->toArray();
    }

    public function userRoles(): Collection
    {
        return $this->user ? $this->user->roles()->get() : new Collection();
    }

    public function userPermissions()
    {
        return $this->user ? $this->user->permissions()->get() : new Collection();
    }
}

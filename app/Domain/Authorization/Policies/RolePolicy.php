<?php

namespace Domain\Authorization\Policies;

use Domain\Authorization\Models\Role;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function update(User $user, Role $role)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }

    public function delete(User $user, Role $role)
    {
        if ($user->isSuperAdmin()) {
            return true;
        }
    }
}

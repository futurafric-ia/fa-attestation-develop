<?php

namespace Domain\Department\Policies;

use Domain\Department\Models\Department;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DepartmentPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if ($user->can('department.list')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->can('department.create')) {
            return true;
        }
    }

    public function update(User $user, Department $department)
    {
        if ($user->can('department.update')) {
            return true;
        }
    }

    public function destroy(User $user, Department $department)
    {
        if ($user->can('department.delete')) {
            return true;
        }
    }
}

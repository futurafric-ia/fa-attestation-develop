<?php

namespace Domain\Authorization\Actions;

use App\Exceptions\GeneralException;
use Domain\Authorization\Models\Role;

final class DestroyRoleAction
{
    public function execute(Role $role): bool
    {
        if ($role->users()->count()) {
            throw new GeneralException('Vous ne pouvez pas supprimer un role assigné à des utilisateurs');
        }

        return Role::destroy($role->id);
    }
}

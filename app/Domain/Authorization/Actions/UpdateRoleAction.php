<?php

namespace Domain\Authorization\Actions;

use Domain\Authorization\Models\Permission;
use Domain\Authorization\Models\Role;

final class UpdateRoleAction
{
    public function execute(Role $role, array $data): Role
    {
        $role->update(['name' => $data['name'], 'description' => $data['description']]);
        $role->syncPermissions($data['permissions'] ?? []);

        if (isset($data['permissions'])) {
            $permissions = Permission::whereIn('id', $data['permissions'])
                ->whereHas('children')
                ->with('children')->get();

            foreach ($permissions as $permission) {
                $role->givePermissionTo($permission->children->pluck('name'));
            }
        }

        return $role;
    }
}

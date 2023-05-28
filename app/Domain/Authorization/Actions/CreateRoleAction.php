<?php

namespace Domain\Authorization\Actions;

use Domain\Authorization\Models\Permission;
use Domain\Authorization\Models\Role;

final class CreateRoleAction
{
    public function execute(array $data): Role
    {
        $role = Role::create([
            'name' => $data['name'],
            'has_department' => $data['has_department'] ?? false,
        ]);

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

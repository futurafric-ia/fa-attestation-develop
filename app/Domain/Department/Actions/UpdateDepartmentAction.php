<?php

namespace Domain\Department\Actions;

use Domain\Department\Models\Department;

final class UpdateDepartmentAction
{
    public function execute(Department $department, array $data): Department
    {
        $department->update([
            'name' => $data['name'] ?? $department->name,
            'description' => $data['description'] ?? $department->description,

        ]);

        return $department->fresh();
    }
}

<?php

namespace Domain\Department\Actions;

use Domain\Department\Models\Department;

final class CreateDepartmentAction
{
    public function execute(array $data): Department
    {
        return Department::query()->create([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
    }
}

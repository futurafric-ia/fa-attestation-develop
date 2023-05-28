<?php

namespace Domain\Department\Actions;

use Domain\Department\Models\Department;

final class DeleteDepartmentAction
{
    public function execute(Department $department)
    {
        $department->delete();
    }
}

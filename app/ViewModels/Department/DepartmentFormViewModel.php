<?php

namespace App\ViewModels\Department;

use Domain\Department\Models\Department;
use Spatie\ViewModels\ViewModel;

class DepartmentFormViewModel extends ViewModel
{
    private ?Department $department;

    public function __construct(Department $department = null)
    {
        $this->department = $department;
    }

    public function department(): ?Department
    {
        return $this->department;
    }
}

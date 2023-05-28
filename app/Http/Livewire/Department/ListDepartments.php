<?php

namespace App\Http\Livewire\Department;

use Domain\Department\Actions\DeleteDepartmentAction;
use Domain\Department\Models\Department;
use Livewire\Component;

class ListDepartments extends Component
{
    public function render()
    {
        return view('livewire.department.list-departments');
    }
}

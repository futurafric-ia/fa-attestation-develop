<?php

namespace App\Http\Livewire\Department;

use Domain\Department\Actions\UpdateDepartmentAction;
use Domain\Department\Models\Department;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditDepartmentForm extends Component
{
    use AuthorizesRequests;

    public $department;

    public $state = [
        'name' => null,
        'description' => null,
    ];

    public function mount(Department $department)
    {
        $this->department = $department;
        $this->state = [
            'name' => $department->name,
            'description' => $department->description,
        ];
    }

    public function saveDepartment(UpdateDepartmentAction $updateDepartmentAction)
    {
        $this->authorize('update', $this->department);

        $this->validate([
            'state.name' => ['required', 'string', 'min:3', Rule::unique('departments', 'name')->ignoreModel($this->department)],
            'state.description' => ['nullable', 'string', 'max:255'],
        ]);

        $updateDepartmentAction->execute($this->department, $this->state);

        session()->flash('success', 'Le département à été mise à jour avec succès.');

        return redirect()->route('backend.departments.index');
    }

    public function render()
    {
        return view('livewire.department.edit-department-form');
    }
}

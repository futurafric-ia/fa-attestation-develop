<?php

namespace App\Http\Livewire\Department;

use Domain\Department\Actions\CreateDepartmentAction;
use Domain\Department\Models\Department;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateDepartmentForm extends Component
{
    use AuthorizesRequests;

    public $state = [
        'name' => null,
        'description' => null,
    ];

    public function saveDepartment(CreateDepartmentAction $createDepartmentAction)
    {
        $this->authorize('create', Department::class);

        $this->validate([
            'state.name' => ['required', 'string', 'min:3', Rule::unique('departments', 'name')],
            'state.description' => ['nullable', 'string', 'max:255'],
        ]);

        $createDepartmentAction->execute($this->state);

        session()->flash('success', 'Le département à été créé avec succès.');

        return redirect()->route('backend.departments.index');
    }

    public function render()
    {
        return view('livewire.department.create-department-form');
    }
}

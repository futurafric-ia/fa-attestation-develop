<?php

namespace App\Http\Livewire\Department;

use Domain\Department\Actions\DeleteDepartmentAction;
use Domain\Department\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DepartmentsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $departmentIdBeingDeleted;
    public $confirmingDepartmentDeletion = false;

    public function query(): Builder
    {
        return Department::query();
    }

    public function columns(): array
    {
        return [
            Column::make('Département', 'name'),
            Column::make('Description', 'description'),
            Column::make('')->format(function ($value, $column, Department $row) {
                return view('livewire.department._table-actions', ['department' => $row]);
            }),
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.department._modals';
    }

    public function confirmDepartmentDeletion($departmentId)
    {
        $this->departmentIdBeingDeleted = $departmentId;
        $this->confirmingDepartmentDeletion = true;
    }

    public function deleteDepartment(DeleteDepartmentAction $action)
    {
        $action->execute(Department::find($this->departmentIdBeingDeleted));
        $this->confirmingDepartmentDeletion = false;

        session()->flash('success', "Le département a été supprimé avec succès !");

        return redirect()->route('backend.departments.index');
    }
}

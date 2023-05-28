<?php

namespace App\Http\Livewire\Authorization;

use Domain\Authorization\Actions\DestroyRoleAction;
use Domain\Authorization\Models\Role;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class RolesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $roleIdBeingDeleted;
    public $confirmingRoleDeletion = false;

    public function query(): Builder
    {
        return Role::query()->withCount('users');
    }

    public function columns(): array
    {
        return [
            Column::make('Rôle', 'name'),
            Column::make('Description'),
            Column::make("Nbr d'utilisateurs associés")->format(fn ($value, $column, Role $row) => $row->users_count),
            Column::make('')->format(fn ($value, $column, Role $row) => view('livewire.authorization._table-actions', ['role' => $row])),
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.authorization._modals';
    }

    public function confirmRoleDeletion($RoleId)
    {
        $this->roleIdBeingDeleted = $RoleId;
        $this->confirmingRoleDeletion = true;
    }

    public function deleteRole(DestroyRoleAction $action)
    {
        $action->execute(Role::find($this->roleIdBeingDeleted));

        $this->confirmingRoleDeletion = false;

        session()->flash('success', "Le rôle a été supprimé avec succès !");

        return redirect()->route('backend.roles.index');
    }
}

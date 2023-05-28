<?php

namespace App\Http\Livewire\Supplier;

use Domain\Supply\Actions\DeleteSupplierAction;
use Domain\Supply\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class SuppliersTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $supplierIdBeingDeleted;
    public $confirmingSupplierDeletion = false;

    public function query(): Builder
    {
        return Supplier::query()->withCount('supplies');
    }

    public function columns(): array
    {
        return [
            Column::make('Code', 'code'),
            Column::make('Nom', 'name')->searchable()->sortable(),
            Column::make('Adresse E-mail', 'email'),
            Column::make('')->format(function ($value, $column, Supplier $row) {
                return view('livewire.supplier._table-actions', ['supplier' => $row]);
            }),
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.supplier._modals';
    }

    public function confirmSupplierDeletion($supplierId)
    {
        $this->supplierIdBeingDeleted = $supplierId;
        $this->confirmingSupplierDeletion = true;
    }

    public function deleteSupplier(DeleteSupplierAction $action)
    {
        $action->execute(Supplier::find($this->supplierIdBeingDeleted));

        $this->confirmingSupplierDeletion = false;

        session()->flash('success', "Le fournisseur a été supprimé avec succès !");

        return redirect()->route('suppliers.index');
    }
}

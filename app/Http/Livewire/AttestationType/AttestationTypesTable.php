<?php

namespace App\Http\Livewire\AttestationType;

use Domain\Attestation\Actions\DeleteAttestationTypeAction;
use Domain\Attestation\Models\AttestationType;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AttestationTypesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $attestationTypeIdBeingDeleted;
    public $confirmingAttestationTypeDeletion = false;

    public function query(): Builder
    {
        return AttestationType::query()->withCount('supplies', 'requests', 'scans');
    }

    public function columns(): array
    {
        return [
            Column::make('Type d\'imprimé', 'name'),
            Column::make('Description', 'description'),
            Column::make('')->format(function ($value, $column, AttestationType $row) {
                return view('livewire.attestation-type._table-actions', ['attestationType' => $row]);
            }),
        ];
    }

    public function modalsView(): string
    {
        return 'livewire.attestation-type._modals';
    }

    public function confirmAttestationTypeDeletion($typeId)
    {
        $this->attestationTypeIdBeingDeleted = $typeId;
        $this->confirmingAttestationTypeDeletion = true;
    }

    public function deleteAttestationType(DeleteAttestationTypeAction $action)
    {
        $action->execute(AttestationType::find($this->attestationTypeIdBeingDeleted));

        $this->confirmingAttestationTypeDeletion = false;
        $this->attestationTypeIdBeingDeleted = null;

        session()->flash('success', "Le type d'imprimé a été supprimé avec succès !");

        return redirect()->route('backend.imprimes.index');
    }
}

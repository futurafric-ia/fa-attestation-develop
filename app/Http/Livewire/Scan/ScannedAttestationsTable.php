<?php

namespace App\Http\Livewire\Scan;

use Domain\Attestation\Models\Attestation;
use Domain\Scan\Models\Scan;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ScannedAttestationsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $sortField = 'attestation_number';

    public Scan $scan;

    public function query(): Builder
    {
        return $this->scan->attestations()->withoutGlobalScopes()->getQuery();
    }

    public function columns(): array
    {
        return [
            Column::make('Numéro attestation', 'attestation_number')->searchable()->sortable(),
            Column::make('Assuré', 'insured_name')->searchable(),
            Column::make('Numéro police', 'police_number')->searchable(),
            Column::make('Immatriculation', 'matriculation')->searchable(),
            Column::make('')->format(function ($value, $column, Attestation $row) {
                return view('livewire.attestations._table-actions', ['attestation' => $row]);
            }),
        ];
    }
}

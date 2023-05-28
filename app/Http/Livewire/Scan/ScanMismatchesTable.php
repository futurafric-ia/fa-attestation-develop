<?php

namespace App\Http\Livewire\Scan;

use Domain\Scan\Models\Scan;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class ScanMismatchesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $sortField = 'attestation_number';

    public Scan $scan;

    public function query(): Builder
    {
        return $this->scan->mismatches()->getQuery()->with('attestationType');
    }

    public function columns(): array
    {
        return [
            Column::make('Numéro attestation', 'attestation_number')->searchable()->sortable(),
            Column::make('Assuré', 'insured_name')->searchable(),
            Column::make('Numéro police', 'police_number')->searchable(),
            Column::make('Immatriculation', 'matriculation')->searchable(),
        ];
    }
}

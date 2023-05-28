<?php

namespace App\Http\Livewire\Attestation;

use App\TableFilters\AttestationStateFilter;
use App\TableFilters\AttestationTypeFilter;
use Domain\Attestation\Models\Attestation;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AnteriorAttestationsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public function query(): Builder
    {
        return Attestation::withoutGlobalScope('currentAttestation')
            ->filter($this->filters)
            ->where('anterior', true)
            ->with('attestationType');
    }

    public function columns(): array
    {
        return [
            Column::make('Numéro attestation', 'attestation_number')->searchable()->sortable(),
            Column::make('Type', 'attestationType.name')
                ->searchable(function ($builder, $term) {
                    return $builder->orWhereHas('attestationType', function ($query) use ($term) {
                        return $query->where('name', 'like', '%' . $term . '%');
                    });
                }),
            Column::make('Statut')->format(function ($value, $column, Attestation $row) {
                return view('livewire.attestation._status', ['attestation' => $row]);
            }),
            Column::make('')->format(function ($value, $column, Attestation $row) {
                return view('livewire.attestation._table-actions', ['attestation' => $row]);
            }),
        ];
    }

    public function filters(): array
    {
        return [
            AttestationTypeFilter::$id => AttestationTypeFilter::make(),
            AttestationStateFilter::$id => AttestationStateFilter::make(),
        ];
    }

    public function displaySlideover($attestationId)
    {
        $this->emit('display-slideover', $attestationId);
    }
}

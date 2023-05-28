<?php

namespace App\Http\Livewire\Delivery;

use App\TableFilters\AttestationStateFilter;
use Domain\Attestation\Models\Attestation;
use Domain\Delivery\Models\Delivery;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DeliveryAttestationsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $sortField = 'attestation_number';
    public Delivery $delivery;

    public function query(): Builder
    {
        return $this->delivery->attestations()->getQuery()->filter($this->filters)->with('attestationType');
    }

    public function columns(): array
    {
        return [
            Column::make('Numéro attestation', 'attestation_number')->searchable()->sortable(),
            Column::make('Type', 'attestationType.name')->searchable(function ($builder, $term) {
                return $builder->orWhereHas('attestationType', function ($query) use ($term) {
                    return $query->where('name', 'like', '%' . $term . '%');
                });
            }),
            Column::make('Assuré', 'insured_name')->searchable(),
            Column::make('Numéro police', 'police_number')->searchable(),
            Column::make('Immatriculation', 'matriculation')->searchable(),
            Column::make('Statut')->format(function ($value, $column, Attestation $row) {
                return view('livewire.attestation._status', ['attestation' => $row]);
            }),
        ];
    }

    public function filters(): array
    {
        return [AttestationStateFilter::$id => AttestationStateFilter::make()];
    }
}

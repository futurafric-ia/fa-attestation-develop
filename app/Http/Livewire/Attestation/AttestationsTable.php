<?php

namespace App\Http\Livewire\Attestation;

use App\Exports\AttestationsExport;
use App\TableFilters\AttestationStateFilter;
use App\TableFilters\AttestationTypeFilter;
use App\TableFilters\PeriodFilter;
use Domain\Attestation\Models\Attestation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class AttestationsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public array $filterNames = [
        'in_the_period' => 'Période',
        'from_date' => "Début de production",
        'to_date' => 'Fin de production',
        'attestation_type' => "Type d'attestation",
        'status' => 'Statut'
    ];

    public array $bulkActions = [
        'exportPdfSelected' => 'Export PDF',
        'exportExcelSelected' => 'Export Excel',
    ];

    public function query(): Builder
    {
        return Attestation::filter($this->filters)->with('attestationType')->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('Numéro attestation', 'attestation_number')->searchable()->sortable(),
            Column::make('Type', 'attestationType.name'),
            Column::make('Statut')->format(
                fn ($value, $column, Attestation $row) => view('livewire.attestation._status', ['attestation' => $row])
            ),
            Column::make('')->format(
                fn ($value, $column, Attestation $row) => view('livewire.attestation._table-actions', ['attestation' => $row])
            ),
        ];
    }

    public function filters(): array
    {
        return [
            AttestationTypeFilter::$id => AttestationTypeFilter::make(),
            AttestationStateFilter::$id => AttestationStateFilter::make(),
            PeriodFilter::$id => PeriodFilter::make("Période de production"),
            'from_date' => Filter::make('Produit du')->date(),
            'to_date' => Filter::make('Au')->date(),
        ];
    }

    public function displaySlideover($attestationId)
    {
        $this->emit('display-slideover', $attestationId);
    }

    public function exportPdfSelected()
    {
        $query = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
        $fileName = 'attestations_' . date('His') . '.pdf';
        $exportPath = \Storage::disk('exports')->path($fileName);

        \PDF::loadView(
            'pdf.listing',
            [
                'title' => 'Liste des attestations',
                'items' => $query->cursor(),
                'columns' => [
                    "Numéro d'attestation" => 'attestation_number',
                    "Type d'imprimés" => 'attestationType.name',
                    'Statut' => fn ($item) => $item->state->label(),
                ],
            ]
        )->save($exportPath);

        return Storage::disk('exports')->download($fileName);
    }

    public function exportExcelSelected()
    {
        $query = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
        $fileName = 'attestations_' . date('His') . '.xlsx';

        return (new AttestationsExport($query))->download($fileName);
    }
}

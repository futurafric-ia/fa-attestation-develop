<?php

namespace App\Http\Livewire\Supply;

use App\Exports\SuppliesExport;
use App\TableFilters\AttestationTypeFilter;
use App\TableFilters\PeriodFilter;
use Domain\Supply\Models\Supply;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class SuppliesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $refresh = 5000;

    public array $filterNames = [
        'attestation_type' => "Type d'attestations",
        'from_date' => "Date de début d'approvisionnement",
        'to_date' => "Date de fin d'approvisionnement",
        'in_the_period' => "Période d'approvisionnement"
    ];

    public array $bulkActions = [
        'exportPdfSelected'   => 'Export PDF',
        'exportExcelSelected' => 'Export Excel',
    ];

    public function query(): Builder
    {
        return Supply::filter($this->filters)->with('attestationType')->latest();
    }

    public function columns(): array
    {
        return [
            Column::make('Code')->sortable(),
            Column::make('Date de réception du lot')->format(
                fn ($value, $column, Supply $row) => $row->received_at->format('d/m/Y')
            ),
            Column::make("Date d'approvisionnement")->format(
                fn ($value, $column, Supply $row) => $row->created_at->format('d/m/Y H:i')
            ),
            Column::make('Type', 'attestationType.name'),
            Column::make('Début série', 'range_start')->searchable()->sortable(),
            Column::make('Fin série', 'range_end')->searchable()->sortable(),
            Column::make('Quantité', 'quantity')->sortable(),
            Column::make('Statut')->format(
                fn ($value, $column, Supply $row) => view('livewire.supply._status', ['supply' => $row])
            ),
        ];
    }

    public function filters(): array
    {
        return [
            AttestationTypeFilter::$id => AttestationTypeFilter::make(),
            PeriodFilter::$id => PeriodFilter::make("Période d'approvisionnement"),
            'from_date' => Filter::make('Approvisionné du')->date(),
            'to_date' => Filter::make('Au')->date(),
        ];
    }

    public function exportPdfSelected()
    {
        $query    = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
        $fileName   = 'approvisionnements_' . date('His') . '.pdf';
        $exportPath = Storage::disk('exports')->path($fileName);

        \PDF::loadView(
            'pdf.listing',
            [
                'title'   => 'Liste des approvisionnements',
                'items'   => $query->cursor(),
                'columns' => [
                    'Code'              => 'code',
                    'Date de reception' => fn ($item) => $item->received_at->format('d/m/Y'),
                    "Date d'approvisionnement" => fn ($item) => $item->created_at->format('d/m/Y H:i'),
                    'Type'              => 'attestationType.name',
                    'Début série'       => 'range_start',
                    'Fin série'         => 'range_end',
                    'Quantité'          => 'quantity',
                ],
            ]
        )->save($exportPath);

        return Storage::disk('exports')->download($fileName);
    }

    public function exportExcelSelected()
    {
        $query = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
        $fileName   = 'approvisionnements_' . date('His') . '.xlsx';

        return (new SuppliesExport($query))->download($fileName);
    }
}

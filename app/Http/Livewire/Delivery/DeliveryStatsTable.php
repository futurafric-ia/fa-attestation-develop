<?php

namespace App\Http\Livewire\Delivery;

use App\TableFilters\AttestationTypeFilter;
use Domain\Delivery\Models\Delivery;
use Domain\Reports\Actions\DeliveryStatsExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DeliveryStatsTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $exportFileName = 'livraisons_stats';
    public $advancedFilters = [];

    public array $bulkActions = [
        'exportPdfSelected' => 'Export PDF',
        'exportExcelSelected' => 'Export Excel',
    ];
    public $exportClass = DeliveryStatsExport::class;

    protected $listeners = [
        'show-delivery-attestations' => 'showDeliveryAttestations',
        'set-advanced-filters' => 'setAdvancedFilters',
    ];

    public function showDeliveryAttestations($deliveryId)
    {
        $this->emitUp('show-delivery-attestations', $deliveryId);
    }

    public function setAdvancedFilters($filters)
    {
        $this->advancedFilters = $filters;
    }

    public function query(): Builder
    {
        return Delivery::filter($this->filters)
            ->advancedFilter($this->advancedFilters)
            ->allowedForUser(auth()->user())
            ->with('broker', 'request', 'attestationType', 'items')
            ->orderBy('created_at', 'desc');
    }

    public function columns(): array
    {
        return [
            Column::make('Intermédiaire', 'broker.name')->hideIf(auth()->user()->isBroker()),
            Column::make('Date livraison')->format(fn ($value, $column, Delivery $row) => $row->created_at->format('d/m/Y')),
            Column::make('Type d\'imprimés', 'attestationType.name'),
            Column::make('Quantité livrée', 'quantity'),
            Column::make('Utilisées')->format(fn ($value, $column, Delivery $row) => $row->totalAttestationCountForState(\Domain\Attestation\States\Used::class)),
            Column::make('Retournées')->format(fn ($value, $column, Delivery $row) => $row->totalAttestationCountForState(\Domain\Attestation\States\Returned::class)),
            Column::make('Annulées')->format(fn ($value, $column, Delivery $row) => $row->totalAttestationCountForState(\Domain\Attestation\States\Cancelled::class)),
            Column::make('Stock')->format(fn ($value, $column, Delivery $row) => $row->totalAttestationCountForState(\Domain\Attestation\States\Available::class)),
            Column::make('')->format(function ($value, $column, Delivery $row) {
                return view('livewire.delivery._reports_table-actions', ['delivery' => $row]);
            }),
        ];
    }

    public function filters(): array
    {
        return [AttestationTypeFilter::$id => AttestationTypeFilter::make()];
    }

    public function exportPdfSelected()
    {
        $this->dispatchBrowserEvent(
            'notice',
            [
                'title' => "L'export a démarré avec succès !",
                'type' => 'success',
                'text' => "Le téléchargement démarrera dans quelques instants",
            ]
        );

        $query = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
        $fileName = 'livraisons_stats_' . date('d_m_Y_His') . '.pdf';
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
        $fileName = 'livraisons_stats_' . date('d_m_Y_His') . '.xlsx';
        $exportPath = \Storage::disk('exports')->path($fileName);

        $getSupplies = function () {
            $query = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
            $items = $query->cursor();

            foreach ($items as $item) {
                yield $item;
            }
        };

        FastExcel::data($getSupplies())->export(
            $exportPath,
            function ($row) {
                return [
                    'Code' => $row->code,
                    'Date de reception' => $row->received_at,
                    'Type' => $row->attestationType->name,
                    'Début série' => $row->range_start,
                    'Fin série' => $row->range_end,
                    'Quantité' => $row->quantity,
                ];
            }
        );

        return Storage::disk('exports')->download($fileName);
    }
}

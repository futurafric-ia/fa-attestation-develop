<?php

namespace App\Http\Livewire\Delivery;

use App\TableFilters\AttestationTypeFilter;
use Domain\Authorization\Models\Role;
use Domain\Delivery\Models\Delivery;
use Domain\Reports\Actions\DeliveryExport;
use Domain\Reports\Actions\NotifyUserOfCompletedExport;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
use PDF;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Spatie\QueueableAction\ActionJob;

class DeliveriesTable extends DataTableComponent
{
    public string $emptyMessage = "Aucun élément trouvé. Essayez d'élargir votre recherche.";

    public $refresh = 5000;

    public array $bulkActions = [
        'exportPdfSelected' => 'Export PDF',
        'exportExcelSelected' => 'Export Excel',
    ];

    public function query(): Builder
    {
        return Delivery::filter($this->filters)
            ->allowedForUser(auth()->user())
            ->with('jobBatch')
            ->with('broker', 'request', 'attestationType', 'items')
            ->orderBy('created_at', 'desc');
    }

    public function filters(): array
    {
        return [AttestationTypeFilter::$id => AttestationTypeFilter::make()];
    }

    public function columns(): array
    {
        return [
            Column::make('Intermédiaire', 'broker.name')->hideIf(auth()->user()->isBroker()),
            Column::make('Type d\'imprimés', 'attestationType.name'),
            Column::make('Quantité livrée', 'quantity'),
            Column::make('Date demande')->format(fn ($value, $column, Delivery $row) => $row->request->created_at->format('d/m/Y')),
            Column::make('Date livraison')->format(fn ($value, $column, Delivery $row) => $row->request->created_at->format('d/m/Y')),
            Column::make('Statut')->format(fn ($value, $column, Delivery $row) => view('livewire.delivery._status', ['delivery' => $row]))->hideIf(auth()->user()->cannot('delivery.create')),
            Column::make('')->format(function ($value, $column, Delivery $row) {
                return view('livewire.delivery._table-actions', ['delivery' => $row]);
            })->hideIf(auth()->user()->hasRole(Role::VALIDATOR)),
        ];
    }

    public function exportPdfSelected()
    {
        $query = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
        $fileName = 'livraisons_' . date('d_m_Y_His') . '.pdf';

        app(DeliveryExport::class)
            ->onQueue('export')
            ->execute($fileName, $this->filters, auth()->user())
            ->chain([new ActionJob(NotifyUserOfCompletedExport::class, [auth()->user(), $fileName])]);

        $this->dispatchBrowserEvent(
            'notice',
            [
                'title' => "L'export a démarré avec succès !",
                'type' => 'success',
                'text' => "Nous vous notifierons lorsqu'il sera terminé.",
            ]
        );

//        dispatch(function () use ($user) {
//            $fileName = \Storage::disk('exports')->path('livraisons_'.date('d_m_Y_His').'.pdf');
//            PDF::loadView(
//                'pdf.listing',
//                [
//                    'title' => 'Liste des livraisons',
//                    'items' => [],
//                    'columns' => [
//                        'Intermédiaire' => 'broker.name',
//                        'Date livraison' => fn ($item) => $item->created_at->format('d/m/Y'),
//                        'Date demande' => fn ($item) => $item->request->created_at->format('d/m/Y'),
//                        "Type d'imprimés" => 'attestationType.name',
//                        'Quantité validée' => 'request.quantity_validated',
//                        'Quantité livrée' => 'request.quantity_delivered',
//                    ],
//                ]
//            )->save($fileName);
//
//            app(NotifyUserOfCompletedExport::class)->execute($user, $fileName);
//        })->onQueue('export');
    }

    public function exportExcelSelected()
    {
        $this->dispatchBrowserEvent(
            'notice',
            [
                'title' => "L'export a démarré avec succès !",
                'type' => 'success',
                'text' => "Le téléchargement démarrera dans quelques instants.",
            ]
        );

        $fileName = 'livraisons_' . date('d_m_Y_His') . '.pdf';
        $exportPath = \Storage::disk('exports')->path($fileName);

        $getDeliveries = function () {
            $query = $this->selectedRowsQuery->count() > 0 ? $this->selectedRowsQuery() : $this->rowsQuery();
            $items = $query->cursor();

            foreach ($items as $item) {
                yield $item;
            }
        };

        FastExcel::data($getDeliveries())->export(
            $exportPath,
            function ($row) {
                return [
                    'Intermédiaire' => $row->broker->name,
                    'Date livraison' => $row->created_at->format('d/m/Y'),
                    'Date demande' => $row->request->created_at->format('d/m/Y'),
                    "Type d'imprimés" => $row->attestationType->name,
                    'Quantité validée' => $row->request->quantity_validated,
                    'Quantité livrée' => $row->request->quantity_delivered,
                ];
            }
        );

        return Storage::disk('exports')->download($fileName);
    }
}

<?php

namespace Domain\Reports\Actions;

use Domain\Supply\Models\Supply;
use Domain\Supply\States\Done;
use Domain\User\Models\User;
use PDF;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class SupplyExport extends Exporter
{
    protected function exportExcel(string $fileName, array $filters = [], ?User $user = null)
    {
        $getSupplies = static function () use ($filters) {
            $items = Supply::filter($filters)->whereState('state', Done::class)->with('attestationType')->cursor();

            foreach ($items as $item) {
                yield $item;
            }
        };

        FastExcel::data($getSupplies())->export(
            $fileName,
            function ($row) {
                return [
                    'Code' => $row->code,
                    'Date de reception' => $row->received_at->format('d/m/Y'),
                    'Type' => $row->attestationType->name,
                    'Début série' => $row->range_start,
                    'Fin série' => $row->range_end,
                    'Quantité' => $row->quantity,
                ];
            }
        );
    }

    protected function exportPdf(string $fileName, array $filters = [], ?User $user = null)
    {
        $items = Supply::filter($filters)->whereState('state', Done::class)->with('attestationType')->cursor();

        PDF::loadView(
            'pdf.listing',
            [
                'title' => 'Liste des approvisionnements',
                'items' => $items,
                'columns' => [
                    'Code' => 'code',
                    'Date de reception' => fn ($item) => $item->received_at->format('d/m/Y'),
                    'Type' => 'attestationType.name',
                    'Début série' => 'range_start',
                    'Fin série' => 'range_end',
                    'Quantité' => 'quantity',
                ],
            ]
        )->save($fileName);
    }
}

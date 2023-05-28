<?php

namespace Domain\Reports\Actions;

use PDF;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class DeliveryExport extends Exporter
{
    protected function exportExcel(string $fileName, array $filters, $user)
    {
        $getDeliveries =  function () {
//            $items = $query->cursor();

//            foreach ($items as $item) {
//                yield $item;
//            }
        };

        FastExcel::data($getDeliveries())->export(
            $fileName,
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
    }

    protected function exportPdf(string $fileName, $query)
    {
        PDF::loadView(
            'pdf.listing',
            [
                'title' => 'Liste des livraisons',
                'items' => $query,
                'columns' => [
                    'Intermédiaire' => 'broker.name',
                    'Date livraison' => fn ($item) => $item->created_at->format('d/m/Y'),
                    'Date demande' => fn ($item) => $item->request->created_at->format('d/m/Y'),
                    "Type d'imprimés" => 'attestationType.name',
                    'Quantité validée' => 'request.quantity_validated',
                    'Quantité livrée' => 'request.quantity_delivered',
                ],
            ]
        )->save($fileName);
    }
}

<?php

namespace Domain\Reports\Actions;

use Domain\Delivery\Models\Delivery;
use Domain\User\Models\User;
use PDF;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class DeliveryStatsExport extends Exporter
{
    protected function exportExcel(string $fileName, array $filters = [], ?User $user = null)
    {
        $getDeliveries = static function () use ($filters, $user) {
            $items = Delivery::filter($filters)
                ->when($user, fn ($q) => $q->allowedForUser($user))
                ->with('broker', 'request', 'attestationType', 'items')
                ->orderBy('created_at', 'desc')
                ->cursor();

            foreach ($items as $item) {
                yield $item;
            }
        };

        FastExcel::data($getDeliveries())->export(
            $fileName,
            function ($row) {
                return [
                    'Intermédiaire' => $row->broker->name,
                    'Date livraison' => $row->created_at->format('d/m/Y'),
                    "Type d'imprimés" => $row->attestationType->name,
                    'Quantité livrée' => $row->request->quantity_delivered,
                    'Utilisées' => $row->totalAttestationCountForState(\Domain\Attestation\States\Used::class),
                    'Retournées' => $row->totalAttestationCountForState(\Domain\Attestation\States\Returned::class),
                    'Annulées' => $row->totalAttestationCountForState(\Domain\Attestation\States\Cancelled::class),
                    'Stock' => $row->totalAttestationCountForState(\Domain\Attestation\States\Available::class),
                ];
            }
        );
    }

    protected function exportPdf(string $fileName, array $filters = [], ?User $user = null)
    {
        $items = Delivery::filter($filters)
            ->when($user, fn ($q) => $q->allowedForUser($user))
            ->with('broker', 'request', 'attestationType', 'items')
            ->orderBy('created_at', 'desc')
            ->cursor();

        PDF::loadView(
            'pdf.listing',
            [
                'title' => 'Statistiques des livraisons',
                'items' => $items,
                'columns' => [
                    'Intermédiaire' => 'broker.name',
                    'Date livraison' => fn ($item) => $item->created_at->format('d/m/Y'),
                    "Type d'imprimés" => 'attestationType.name',
                    'Quantité livrée' => 'request.quantity_delivered',
                    'Utilisées' => fn ($item) => $item->totalAttestationCountForState(\Domain\Attestation\States\Used::class),
                    'Retournées' => fn ($item) => $item->totalAttestationCountForState(\Domain\Attestation\States\Returned::class),
                    'Annulées' => fn ($item) => $item->totalAttestationCountForState(\Domain\Attestation\States\Cancelled::class),
                    'Stock' => fn ($item) => $item->totalAttestationCountForState(\Domain\Attestation\States\Available::class),
                ],
            ]
        )->save($fileName);
    }
}

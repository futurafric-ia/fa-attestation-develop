<?php

namespace Domain\Reports\Actions;

use Domain\Attestation\Models\Attestation;
use Domain\User\Models\User;
use PDF;
use Rap2hpoutre\FastExcel\Facades\FastExcel;

class AttestationExport extends Exporter
{
    protected function exportExcel(string $fileName, $query)
    {
        $getAttestations = static function () {
            $items = Attestation::filter($filters)->with('attestationType')->cursor();

            foreach ($items as $item) {
                yield $item;
            }
        };

        FastExcel::data($getAttestations())->export(
            $fileName,
            function ($attestation) {
                return [
                    "Numéro d'attestation" => $attestation->attestation_number,
                    "Type d'imprimés" => $attestation->attestationType->name,
                    'Statut' => $attestation->state->label(),
                ];
            }
        );
    }

    protected function exportPdf(string $fileName, $query)
    {
        PDF::loadView(
            'pdf.listing',
            [
                'title' => 'Liste des attestations',
                'items' => Attestation::whereIn('id', $query)->cursor(),
                'columns' => [
                    "Numéro d'attestation" => 'attestation_number',
                    "Type d'imprimés" => 'attestationType.name',
                    'Statut' => fn ($item) => $item->state->label(),
                ],
            ]
        )->save($fileName);
    }
}

<?php

namespace Domain\Supply\Actions;

use Domain\Attestation\Models\Attestation;
use Illuminate\Validation\ValidationException;

final class ValidateSupplyRangeAction
{
    public function execute(array $ranges)
    {
        /**
         * Vérifies si les numéros de série sont valides
         */
        $invalidRanges = [];

        $rangesHaveValidStart = collect($ranges)->every(function ($range) use (&$invalidRanges) {
            $result = $range['range_start'] <= $range['range_end'];

            if (! $result) {
                $invalidRanges[] = implode('-', [$range['range_start'], $range['range_end']]);
            }

            return $result;
        });

        throw_if(! $rangesHaveValidStart, ValidationException::withMessages([
            'attestation_range' => ["Ces plages d'attestations sont invalides: " . implode(',', $invalidRanges)],
        ]));


        /**
         * Verifies si les plages renseignées contiennent des attestations déjà approvisionnées.
         */
        $invalidAttestationNumbers = [];

        $attestationsCount = array_reduce($ranges, function (int $acc, $current) use (&$invalidAttestationNumbers) {
            $items = Attestation::query()
                ->whereBetween('attestation_number', [$current['range_start'], $current['range_end']])
                ->get();

            if ($items->isNotEmpty()) {
                $invalidAttestationNumbers = [...$invalidAttestationNumbers, ...$items->map(fn ($x) => $x->attestation_number)];
            }

            return $acc + $items->count();
        }, 0);

        throw_if($attestationsCount !== 0, ValidationException::withMessages([
            'attestation_range' => [
                sprintf(
                    "Cette plage d'attestations contient des attestations déjà approvisionnées: %s",
                    implode(', ', $invalidAttestationNumbers)
                ),
            ],
        ]));
    }
}

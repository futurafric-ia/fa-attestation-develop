<?php

namespace Domain\Delivery\Actions;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\States\Available;
use Domain\Attestation\States\Returned;
use Domain\Request\Models\Request;
use Illuminate\Validation\ValidationException;

final class ValidateDeliveryRangeAction
{
    public function execute(Request $request, array $ranges): bool
    {
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

        $actualCount = array_reduce($ranges, function (int $acc, $current) {
            $acc += Attestation::query()
                ->whereBetween('attestation_number', [(int) $current['range_start'], (int) $current['range_end']])
                ->whereState('state', [Available::class, Returned::class])
                ->count();

            return $acc;
        }, 0);

        throw_if($actualCount !== $request->quantity_validated, ValidationException::withMessages([
            'attestation_range' => [
                "Le nombre total d'attestations de type {$request->attestationType->name} pouvant être livrées dans les plages renseignées est égal à {$actualCount}.",
            ],
        ]));

        return true;
    }
}

<?php

namespace Domain\Scan\Actions;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\States\Attributed;
use Illuminate\Validation\ValidationException;

final class ValidateScanRangeAction
{
    public function execute(array $ranges, int $expectedCount): bool
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
                ->whereBetween('attestation_number', [$current['range_start'], $current['range_end']])
                ->whereState('state', Attributed::class)
                ->count();

            return $acc;
        }, 0);

        throw_if($actualCount !== $expectedCount, ValidationException::withMessages([
            'attestation_range' => [
                "Le nombre total d'attestations évalué ({$actualCount}) ne correspond pas au nombre d'attestations attendu ({$expectedCount})",
            ],
        ]));

        return true;
    }
}

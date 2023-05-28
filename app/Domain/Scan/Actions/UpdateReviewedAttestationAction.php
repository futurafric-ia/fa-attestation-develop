<?php

namespace App\Domain\Scan\Actions;

use DB;
use Domain\Attestation\Models\Attestation;
use Domain\Attestation\States\Attributed;
use Domain\Scan\Models\Scan;
use Domain\Supply\Models\Supply;
use Illuminate\Validation\ValidationException;

final class UpdateReviewedAttestationAction
{
    public function execute(Scan $scan, array $data): Attestation
    {
        return DB::transaction(function () use ($scan, $data) {
            /**
            * @var Attestation $attestation
            */
            $attestation = Attestation::query()
                ->withoutGlobalScope('currentAttestation')
                ->where('attestation_number', $data['attestation_number'])
                ->first();

            if (! $attestation) {
                $attestation = Attestation::create([
                    'attestation_number' => $data['attestation_number'],
                    'attestation_type_id' => $scan->attestation_type_id,
                    'supply_id' => Supply::withoutGlobalScopes()->firstWhere(['range_start' => config('saham.anterior_supply_attestation_number.' . $scan->attestation_type_id)])->id,
                    'state' => Attributed::$name,
                    'source' => 'system',
                    'anterior' => true,
                ]);
            }

            throw_if(! $attestation->hasState(Attributed::$name), ValidationException::withMessages([
                'attestation' => [
                    "Vous ne pouvez enregistrer qu'une attestation de statut `Attribuée`. L'attestation désignée a un statut `{$attestation->state->label()}`.",
                ],
            ]));

            $attestation->update(array_merge($data, ['last_scan_id' => $scan->id, 'current_broker_id' => $scan->broker_id]));

            if ($attestation->state->canTransitionTo($scan->attestation_state)) {
                $attestation->state->transitionTo($scan->attestation_state);
            }

            $attestation->scans()->attach($scan);

            if ($scan->mismatches_count > 0) {
                $scan->decrement('mismatches_count');
            }

            return $attestation->fresh();
        });
    }
}

<?php

namespace App\Domain\Scan\Actions;

use Domain\Attestation\Parsers\AttestationParserFactory;
use Domain\Delivery\Models\Delivery;
use Illuminate\Validation\ValidationException;

class ValidateManualScanAction
{
    public function execute(array $data)
    {
        $brokerHasDelivery = Delivery::where(['broker_id' => $data['broker_id'], 'attestation_type_id' => $data['attestation_type_id']])->exists();

        throw_if(! $brokerHasDelivery, ValidationException::withMessages([
            'broker' => "L'intermédiaire n'a aucune livraison de ce type.",
        ]));

        $parser = AttestationParserFactory::make($data['attestation_type_id']);

        $hasValidAttestationNumber = array_reduce($data['items'], function ($acc, $current) use ($parser) {
            $hasValidStart = $parser->getAttestationNumber($current['range_start']);
            $hasValidEnd = $parser->getAttestationNumber($current['range_end']);

            return $acc && $hasValidStart && $hasValidEnd;
        }, true);

        throw_if(! $hasValidAttestationNumber, ValidationException::withMessages([
            'broker' => "Les numéros d'attestations renseignés ne sont pas conforme au type sélectionné.",
        ]));
    }
}

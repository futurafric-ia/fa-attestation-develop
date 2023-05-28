<?php

namespace App\Domain\Scan\Actions;

use Domain\Delivery\Models\Delivery;
use Illuminate\Validation\ValidationException;

class ValidateOcrScanAction
{
    public function execute(array $data)
    {
        $brokerHasDeliveryOfType = Delivery::where(['broker_id' => $data['broker_id'], 'attestation_type_id' => $data['attestation_type_id']])->exists();

        throw_if(! $brokerHasDeliveryOfType, ValidationException::withMessages([
            'broker' => "L'intermédiaire n'a aucune livraison de ce type.",
        ]));
    }
}

<?php

namespace Domain\Request\Actions;

use Domain\Analytics\Analytics;
use Domain\Attestation\Models\AttestationType;
use Domain\Request\Models\Request;
use Illuminate\Validation\ValidationException;

class ValidateStockAction
{
    public function execute(Request $request, int $quantity)
    {
        if ($quantity < 1) {
            throw ValidationException::withMessages(['insufficient_stock' => ['Veuillez spécifier une quantité supérieure où égale à 1.']]);
        }

        $analytics = new Analytics();
        $availableStock = $analytics->totalAvailableStockForType($request->attestation_type_id);

        if ($availableStock < $quantity) {
            throw ValidationException::withMessages(['insufficient_stock' => ['La quantité à livrer est supérieure au stock disponible.']]);
        }

        if ($request->isOfType(AttestationType::YELLOW)) {
            $availableBrownStock = $analytics->totalAvailableBrownStock();

            if ($availableBrownStock < $quantity) {
                throw ValidationException::withMessages(['insufficient_stock' => ['Le stock disponible d\'attestations brunes est supérieure à la quantité à livrer d\'attestations jaunes.']]);
            }
        }
    }
}

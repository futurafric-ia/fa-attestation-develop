<?php

namespace Domain\Request\Actions;

use Domain\Request\Models\Request;

final class UpdateRequestAction
{
    public function execute(Request $request, array $data): Request
    {
        $request->update([
            'quantity' => $data['quantity'] ?? $request->quantity,
            'quantity_validated' => $data['quantity_validated'] ?? $request->quantity_validated,
            'quantity_approved' => $data['quantity_approved'] ?? $request->quantity_approved,
            'notes' => $data['notes'] ?? $request->notes,
            'attestation_type_id' => $data['attestation_type_id'] ?? $request->attestation_type_id,
            'expected_at' => $data['expected_at'] ?? $request->expected_at,
        ]);

        return $request->fresh();
    }
}

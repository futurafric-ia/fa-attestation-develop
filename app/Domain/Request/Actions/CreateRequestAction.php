<?php

namespace Domain\Request\Actions;

use Domain\Request\Models\Request;
use Domain\Request\States\Pending;

final class CreateRequestAction
{
    public function execute(array $data): Request
    {
        return Request::query()->create([
            'quantity' => $data['quantity'],
            'notes' => $data['notes'],
            'attestation_type_id' => $data['attestation_type_id'],
            'broker_id' => $data['broker_id'],
            'expected_at' => $data['expected_at'],
            'created_by' => $data['created_by'],
            'state' => Pending::$name
        ]);
    }
}

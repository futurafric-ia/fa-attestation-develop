<?php

namespace Domain\Attestation\Actions;

use Domain\Attestation\Models\AttestationType;

final class CreateAttestationTypeAction
{
    public function execute(array $data): AttestationType
    {
        return AttestationType::query()->create([
            'name' => $data['name'],
            'color' => $data['color'],
            'description' => $data['description'],
        ]);
    }
}

<?php

namespace Domain\Attestation\Actions;

use Domain\Attestation\Models\AttestationType;

final class UpdateAttestationTypeAction
{
    public function execute(AttestationType $attestationType, array $data): AttestationType
    {
        $attestationType->update([
            'name' => $data['name'] ?? $attestationType->name,
            'color' => $data['color'] ?? $attestationType->color,
            'description' => $data['description'] ?? $attestationType->description,
        ]);

        return $attestationType->fresh();
    }
}

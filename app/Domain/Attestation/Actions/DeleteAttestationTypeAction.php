<?php

namespace Domain\Attestation\Actions;

use Domain\Attestation\Models\AttestationType;

final class DeleteAttestationTypeAction
{
    public function execute(AttestationType $attestationType)
    {
        $attestationType->delete();
    }
}

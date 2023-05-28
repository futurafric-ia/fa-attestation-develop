<?php

namespace Domain\Attestation\Rules;

use Domain\Attestation\Models\Attestation;
use Illuminate\Contracts\Validation\Rule;

class AttestationExists implements Rule
{
    private $attestationType;

    public function __construct(?int $attestationType = null)
    {
        $this->attestationType = $attestationType;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->attestationType ? Attestation::query()
            ->withoutGlobalScope('currentAttestation') // Include anterior attestations in the check
            ->where('attestation_type_id', $this->attestationType)
            ->where('attestation_number', $value)
            ->exists() : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Ce numéro d\'attestation n\'existe pas pour ce type';
    }
}

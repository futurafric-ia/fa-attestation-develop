<?php

namespace Domain\Attestation\States;

final class Used extends AttestationState
{
    public static $name = 'used';

    public function color(): string
    {
        return 'bg-blue-500';
    }

    public function label(): string
    {
        return 'Utilisée';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

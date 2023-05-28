<?php

namespace Domain\Attestation\States;

final class Available extends AttestationState
{
    public static $name = 'available';

    public function color(): string
    {
        return 'bg-green-500';
    }

    public function label(): string
    {
        return 'Disponible';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

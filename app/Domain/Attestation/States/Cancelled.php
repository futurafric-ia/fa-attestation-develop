<?php

namespace Domain\Attestation\States;

final class Cancelled extends AttestationState
{
    public static $name = 'cancelled';

    public function color(): string
    {
        return 'bg-red-500';
    }

    public function label(): string
    {
        return 'Annulée';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

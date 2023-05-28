<?php

namespace Domain\Attestation\States;

final class Attributed extends AttestationState
{
    public static $name = 'attributed';

    public function color(): string
    {
        return 'bg-orange-500';
    }

    public function label(): string
    {
        return 'Attribuée';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

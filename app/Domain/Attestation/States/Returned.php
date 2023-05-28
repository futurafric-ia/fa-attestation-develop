<?php

namespace Domain\Attestation\States;

final class Returned extends AttestationState
{
    public static $name = 'returned';

    public function color(): string
    {
        return 'bg-yellow-400';
    }

    public function label(): string
    {
        return 'Retournée';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

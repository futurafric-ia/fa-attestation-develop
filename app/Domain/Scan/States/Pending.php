<?php

namespace Domain\Scan\States;

final class Pending extends ScanState
{
    public static $name = 'pending';

    public function label(): string
    {
        return 'En attente';
    }

    public function color(): string
    {
        return 'bg-gray-400';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

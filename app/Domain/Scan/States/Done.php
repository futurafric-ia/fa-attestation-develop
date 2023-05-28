<?php

namespace Domain\Scan\States;

final class Done extends ScanState
{
    public static $name = 'done';

    public function color(): string
    {
        return 'bg-green-500';
    }

    public function label(): string
    {
        return 'Terminé';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

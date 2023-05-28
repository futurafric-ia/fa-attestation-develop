<?php

namespace Domain\Scan\States;

final class Failed extends ScanState
{
    public static $name = 'failed';

    public function color(): string
    {
        return 'bg-red-500';
    }

    public function label(): string
    {
        return 'Echoué';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

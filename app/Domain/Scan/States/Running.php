<?php

namespace Domain\Scan\States;

class Running extends ScanState
{
    public static $name = 'running';

    public function label(): string
    {
        return 'En cours';
    }

    public function color(): string
    {
        return 'bg-orange-500';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

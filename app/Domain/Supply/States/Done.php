<?php

namespace Domain\Supply\States;

class Done extends SupplyState
{
    public static $name = 'done';

    public function label(): string
    {
        return 'Terminé';
    }

    public function color(): string
    {
        return 'bg-green-500';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

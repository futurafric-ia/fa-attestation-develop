<?php

namespace Domain\Supply\States;

class Pending extends SupplyState
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

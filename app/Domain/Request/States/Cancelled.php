<?php

namespace Domain\Request\States;

class Cancelled extends RequestState
{
    public static $name = 'cancelled';

    public function label(): string
    {
        return 'Annulée';
    }

    public function color(): string
    {
        return 'bg-red-500';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

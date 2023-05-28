<?php

namespace Domain\Request\States;

class Pending extends RequestState
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

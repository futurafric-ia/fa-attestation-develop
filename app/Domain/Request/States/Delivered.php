<?php

namespace Domain\Request\States;

class Delivered extends RequestState
{
    public static $name = 'delivered';

    public function label(): string
    {
        return 'Livrée';
    }

    public function color(): string
    {
        return 'bg-blue-500';
    }

    public function textColor(): string
    {
        return 'text-gray-50';
    }
}

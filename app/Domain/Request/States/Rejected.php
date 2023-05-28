<?php

namespace Domain\Request\States;

class Rejected extends RequestState
{
    public static $name = 'rejected';

    public function label(): string
    {
        return 'Rejetée';
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

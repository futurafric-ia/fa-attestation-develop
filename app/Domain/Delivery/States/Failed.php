<?php

namespace Domain\Delivery\States;

class Failed extends DeliveryState
{
    public static $name = 'failed';

    public function label(): string
    {
        return 'Echoué';
    }

    public function color(): string
    {
        return 'bg-red-500';
    }

    public function textColor(): ?string
    {
        return 'text-gray-50';
    }
}

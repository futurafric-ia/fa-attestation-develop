<?php

namespace Domain\Delivery\States;

class Done extends DeliveryState
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

    public function textColor(): ?string
    {
        return 'text-gray-50';
    }
}

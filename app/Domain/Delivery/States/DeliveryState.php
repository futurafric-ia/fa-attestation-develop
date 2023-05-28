<?php

namespace Domain\Delivery\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class DeliveryState extends State
{
    abstract public function label(): string;

    abstract public function color(): string;

    abstract public function textColor(): ?string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Running::class)
            ->allowTransition(Running::class, Done::class)
            ->allowTransition(Failed::class, Running::class)
            ->allowTransition(Running::class, Failed::class);
    }
}

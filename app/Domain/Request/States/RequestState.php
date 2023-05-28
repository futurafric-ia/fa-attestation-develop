<?php

namespace Domain\Request\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class RequestState extends State
{
    abstract public function label(): string;

    abstract public function color(): string;

    abstract public function textColor(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Cancelled::class, PendingToCancelled::class)
            ->allowTransition(Pending::class, Rejected::class, PendingToRejected::class)
            ->allowTransition(Pending::class, Approved::class)
            ->allowTransition(Approved::class, Validated::class)
            ->allowTransition(Validated::class, Delivered::class);
    }
}

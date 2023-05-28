<?php

namespace Domain\Attestation\States;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class AttestationState extends State
{
    abstract public function color(): string;

    abstract public function label(): string;

    abstract public function textColor(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Available::class)
            ->allowTransition(Available::class, Attributed::class)
            ->allowTransition(Attributed::class, Cancelled::class)
            ->allowTransition(Attributed::class, Used::class)
            ->allowTransition(Attributed::class, Returned::class);
    }
}

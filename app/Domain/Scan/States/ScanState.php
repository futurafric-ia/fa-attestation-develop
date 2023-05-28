<?php

namespace Domain\Scan\States;

use Domain\Attestation\States\AttestationState;
use Domain\Attestation\States\Used;
use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class ScanState extends State
{
    abstract public function color(): string;

    abstract public function label(): string;

    abstract public function textColor(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(Pending::class)
            ->allowTransition(Pending::class, Running::class)
            ->allowTransition(Running::class, Failed::class)
            ->allowTransition(Failed::class, Running::class)
            ->allowTransition(Running::class, Done::class);
    }


    protected function registerStates(): void
    {
        $this->addState('attestation_state', AttestationState::class)->default(Used::class);
    }
}

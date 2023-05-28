<?php

namespace Domain\Supply\Listeners;

use Domain\Supply\Events\SupplyCreated;
use Domain\Supply\Events\SupplyDone;
use Domain\Supply\Events\SupplyFailed;
use Domain\Supply\Events\SupplyStarted;
use Domain\Supply\States\Done;
use Domain\Supply\States\Failed;
use Domain\Supply\States\Running;
use Domain\User\Models\User;

class SupplyEventListener
{
    public function onCreated($event)
    {
        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $impersonator = User::find($manager->getImpersonatorId());
            $loggedInUser = auth()->user()->full_name;

            activity('Approvisionnement')
                ->performedOn($event->supply)
                ->withProperties(['event' => "Demande en cours de traitement"])
                ->log("{$impersonator->full_name} a approvisionné {$event->supply->quantity} attestations de type {$event->supply->attestationType->name} à la place de {$loggedInUser}");
        } else {
            activity('Approvisionnement')
                ->performedOn($event->supply)
                ->withProperties([
                    'supply' => [
                        'type' => $event->supply->attestationType->name,
                        'quantity' => $event->supply->quantity,
                        'broker_name' => $event->broker->name,
                    ],
                ])
                ->log(':causer.full_name a approvisionné :properties.supply.quantity attestations de type :properties.supply.type à :properties.supply.broker_name');
        }
    }

    public function onStarted($event)
    {
        $event->supply->state->transitionTo(Running::class);
    }

    public function onCompleted($event)
    {
        $event->supply->state->transitionTo(Done::class);
    }

    public function onFailed($event)
    {
        $event->supply->state->transitionTo(Failed::class);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            SupplyCreated::class,
            'Domain\Supply\Listeners\SupplyEventListener@onCreated'
        );

        $events->listen(
            SupplyStarted::class,
            'Domain\Supply\Listeners\SupplyEventListener@onStarted'
        );

        $events->listen(
            SupplyDone::class,
            'Domain\Supply\Listeners\SupplyEventListener@onCompleted'
        );

        $events->listen(
            SupplyFailed::class,
            'Domain\Supply\Listeners\SupplyEventListener@onFailed'
        );
    }
}

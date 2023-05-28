<?php

namespace Domain\Delivery\Listeners;

use Domain\Delivery\Events\DeliveryCreated;
use Domain\Delivery\Events\DeliveryDone;
use Domain\Delivery\Events\DeliveryFailed;
use Domain\Delivery\Events\DeliveryStarted;
use Domain\Delivery\States\Done;
use Domain\Delivery\States\Failed;
use Domain\Delivery\States\Running;
use Domain\User\Models\User;

class DeliveryEventListener
{
    public function onCreated($event)
    {
        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $impersonator = User::find($manager->getImpersonatorId());
            $loggedInUser = auth()->user()->full_name;

            activity('Livraison')
                ->performedOn($event->delivery)
                ->log("{$impersonator->full_name} a livré {$event->delivery->quantity} attestations de type {$event->delivery->attestationType->name} à {$event->delivery->broker->name} à la place de {$loggedInUser}");
        } else {
            activity('Livraison')
                ->performedOn($event->delivery)
                ->withProperties([
                    'delivery' => [
                        'type' => $event->delivery->attestationType->name,
                        'quantity' => $event->delivery->quantity,
                        'broker_name' => $event->delivery->broker->name,
                    ],
                ])
                ->log(':causer.full_name a livré :properties.delivery.quantity attestations de type :properties.delivery.type à :properties.delivery.broker_name');
        }
    }

    public function onStarted($event)
    {
        $event->delivery->state->transitionTo(Running::class);
    }

    public function onCompleted($event)
    {
        $event->delivery->state->transitionTo(Done::class);
    }

    public function onFailed($event)
    {
        $event->delivery->state->transitionTo(Failed::class);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            DeliveryCreated::class,
            'Domain\Delivery\Listeners\DeliveryEventListener@onCreated'
        );

        $events->listen(
            DeliveryStarted::class,
            'Domain\Delivery\Listeners\DeliveryEventListener@onStarted'
        );

        $events->listen(
            DeliveryDone::class,
            'Domain\Delivery\Listeners\DeliveryEventListener@onCompleted'
        );

        $events->listen(
            DeliveryFailed::class,
            'Domain\Delivery\Listeners\DeliveryEventListener@onFailed'
        );
    }
}

<?php

namespace Domain\Scan\Listeners;

use Domain\Scan\Events\ScanCompleted;
use Domain\Scan\Events\ScanCreated;
use Domain\Scan\Events\ScanFailed;
use Domain\Scan\Events\ScanStarted;
use Domain\Scan\States\Done;
use Domain\Scan\States\Failed;
use Domain\Scan\States\Running;
use Domain\User\Models\User;

class ScanEventListener
{
    public function onCreated(ScanCreated $event)
    {
        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $impersonator = User::find($manager->getImpersonatorId());
            $loggedInUser = auth()->user()->full_name;

            activity('Scan')
                ->performedOn($event->scan)
                ->log("{$impersonator->full_name} a scanné {$event->scan->total_count} attestations de type {$event->scan->attestationType->name} de {$event->scan->broker->name} à la place de {$loggedInUser}");
        } else {
            activity('Scan')
                ->performedOn($event->scan)
                ->withProperties([
                    'scan' => [
                        'type' => $event->scan->attestationType->name,
                        'quantity' => $event->scan->total_count,
                        'broker_name' => $event->scan->broker->name,
                    ],
                ])
                ->log(':causer.full_name a scanné :properties.scan.quantity attestations de type :properties.scan.type de :properties.scan.broker_name');
        }
    }

    public function onStarted(ScanStarted $event)
    {
        $event->scan->state->transitionTo(Running::class);
    }

    public function onCompleted(ScanCompleted $event)
    {
        $event->scan->state->transitionTo(Done::class);
    }

    public function onFailed(ScanFailed $event)
    {
        $event->scan->state->transitionTo(Failed::class);
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function subscribe($events)
    {
        $events->listen(
            ScanCreated::class,
            'Domain\Scan\Listeners\ScanEventListener@onCreated'
        );

        $events->listen(
            ScanStarted::class,
            'Domain\Scan\Listeners\ScanEventListener@onStarted'
        );

        $events->listen(
            ScanCompleted::class,
            'Domain\Scan\Listeners\ScanEventListener@onCompleted'
        );

        $events->listen(
            ScanFailed::class,
            'Domain\Scan\Listeners\ScanEventListener@onFailed'
        );
    }
}

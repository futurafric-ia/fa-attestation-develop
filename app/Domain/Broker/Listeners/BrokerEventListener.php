<?php

namespace Domain\Broker\Listeners;

use Domain\Broker\Events\BrokerCreated;
use Domain\Broker\Events\BrokerDeleted;
use Domain\Broker\Events\BrokerUpdated;

class BrokerEventListener
{
    public function onCreated($event)
    {
        activity('Création d\'un intermédiaire')
            ->performedOn($event->broker)
            ->withProperties([
                'broker' => [
                    'name' => $event->broker->name,
                    'code' => $event->broker->code,
                ],
            ])
            ->log(':causer.full_name a créé le compte de l\'intermédiaire :subject.name (:subject.code)');
    }

    public function onDeleted($event)
    {
        activity('Suppression d\'un intermédiaire')
        ->performedOn($event->broker)
            ->withProperties([
                'broker' => [
                    'name' => $event->broker->name,
                    'code' => $event->broker->code,
                ],
            ])
            ->log(':causer.full_name a supprimé le compte d\'un intermédiaire :subject.name (:subject.code)');
    }


    public function onUpdated($event)
    {
        activity('Mise à jour d\'un intermédiaire')
        ->performedOn($event->broker)
            ->withProperties([
                'broker' => [
                    'name' => $event->broker->name,
                    'code' => $event->broker->code,
                ],
            ])
            ->log(':causer.full_name a modifié le compte de l\'intermédiaire :subject.name (:subject.code)');
    }


    public function subscribe($events)
    {
        $events->listen(
            BrokerCreated::class,
            'Domain\Broker\Listeners\BrokerEventListener@onCreated'
        );

        $events->listen(
            BrokerDeleted::class,
            'Domain\Broker\Listeners\BrokerEventListener@onDeleted'
        );

        $events->listen(
            BrokerUpdated::class,
            'Domain\Broker\Listeners\BrokerEventListener@onUpdated'
        );
    }
}

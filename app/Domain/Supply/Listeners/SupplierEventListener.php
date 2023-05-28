<?php

namespace Domain\Supply\Listeners;

use Domain\Supply\Events\SupplierCreated;
use Domain\Supply\Events\SupplierDeleted;
use Domain\Supply\Events\SupplierUpdated;

class SupplierEventListener
{
    public function onCreated($event)
    {
        activity('Création d\'un fournisseur')
            ->performedOn($event->supplier)
            ->withProperties([
                'supplier' => [
                    'name' => $event->supplier->name,
                ],
            ])
            ->log(':causer.full_name a créé le compte du fournisseur :properties.supplier.name');
    }

    public function onDeleted($event)
    {
        activity('Supression d\'un fournisseur')
            ->performedOn($event->supplier)
            ->withProperties([
                'supplier' => [
                    'name' => $event->supplier->name,
                ],
            ])
            ->log(':causer.full_name a supprimé le compte du fournisseur :properties.supplier.name');
    }

    public function onUpdated($event)
    {
        activity('Mise à jour d\'un fournisseur')
            ->performedOn($event->supplier)
            ->withProperties([
                'supplier' => [
                    'name' => $event->supplier->name,
                ],
            ])
            ->log(':causer.full_name a modifié le compte du fournisseur :properties.supplier.name');
    }

    public function subscribe($events)
    {
        $events->listen(
            SupplierCreated::class,
            'Domain\Supply\Listeners\SupplierEventListener@onCreated'
        );

        $events->listen(
            SupplierDeleted::class,
            'Domain\Supply\Listeners\SupplierEventListener@onDeleted'
        );

        $events->listen(
            SupplierUpdated::class,
            'Domain\Supply\Listeners\SupplierEventListener@onUpdated'
        );
    }
}

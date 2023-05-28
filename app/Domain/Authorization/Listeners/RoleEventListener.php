<?php

namespace Domain\Authorization\Listeners;

use Domain\Authorization\Events\RoleCreated;
use Domain\Authorization\Events\RoleDestroyed;
use Domain\Authorization\Events\RoleUpdated;

class RoleEventListener
{
    public function onCreated($event)
    {
        activity('Création d\'un rôle')
            ->performedOn($event->role)
            ->withProperties([
                'role' => [
                    'name' => $event->role->quantity,
                    'has_department' => $event->role->has_department,
                    'permissions' => "",
                    'author' => auth()->user()->full_name ?? "Un Script",
                ],
            ])
            ->log(':properties.role.author a créé le rôle :properties.role.name pour le département :properties.role.has_department');
        // ->log(':causer.full_name a créé le rôle :properties.role.name pour le département :properties.role.has_department');
    }

    public function onUpdated($event)
    {
        activity('Mise à jour d\'un rôle')
        ->performedOn($event->role)
            ->withProperties([
                'role' => [
                    //'last_name' => $event->last_name,
                    'name' => $event->role->quantity,
                    'has_department' => $event->role->has_department,
                    'permissions' => "",
                    'author' => auth()->user()->full_name ?? "Un Script",
                ],
            ])
            ->log(':properties.role.author a modifié le rôle :properties.role.name ');
    }

    public function onDetroyed($event)
    {
        activity('Suppression d\' unrole')
        ->performedOn($event->role)
            ->withProperties([
                'role' => [
                    'name' => $event->role->quantity,
                    'has_department' => $event->role->has_department,
                    'permissions' => "",
                    'author' => auth()->user()->full_name ?? "Un Script",
                ],
            ])
            ->log(':properties.role.author a suprrimé le rôle :properties.role.name pour le département :properties.role.has_department');
    }

    public function subscribe($events)
    {
        $events->listen(
            RoleCreated::class,
            'Domain\Role\Listeners\RoleEventListener@onCreated'
        );

        $events->listen(
            RoleDestroyed::class,
            'Domain\Role\Listeners\RoleEventListener@onDetroyed'
        );

        $events->listen(
            RoleUpdated::class,
            'Domain\Role\Listeners\RoleEventListener@onUpdated'
        );
    }
}

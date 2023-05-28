<?php

namespace Domain\User\Listeners;

use Domain\User\Events\UserCreated;
use Domain\User\Events\UserDeleted;
use Domain\User\Events\UserLoggedIn;
use Domain\User\Events\UserUpdated;
use Illuminate\Auth\Events\PasswordReset;

class UserEventListener
{
    public function onCreated($event)
    {
        activity('Création d\'un utilisateur')
        ->performedOn($event->user)
            ->withProperties([
                'user' => [
                    'name' => $event->user->full_name,
                ],
            ])
            ->log(':causer.full_name a créé  :properties.user.name');
    }

    public function onUpdated($event)
    {
        activity('Mise à jour d\'un utilisateur')
        ->performedOn($event->user)
            ->withProperties([
                'user' => [
                    'name' => $event->user->full_name,
                ],
            ])
            ->log(':causer.full_name a mise à jour le compte de l\'utilisateur :properties.user.name');
    }


    public function onDeleted($event)
    {
        activity('Suppression d\'un utilisateur')
        ->performedOn($event->user)
            ->withProperties([
                'user' => [
                    'name' => $event->user->full_name,
                ],
            ])
            ->log(':causer.full_name a supprimé :properties.user.name');
    }

    public function onLoggedIn($event)
    {
        $event->user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->getClientIp(),
        ]);
    }

    public function onPasswordReset($event)
    {
        $event->user->update(['password_changed_at' => now()]);
    }

    public function subscribe($events)
    {
        $events->listen(
            UserLoggedIn::class,
            'Domain\User\Listeners\UserEventListener@onLoggedIn'
        );

        $events->listen(
            PasswordReset::class,
            'Domain\User\Listeners\UserEventListener@onPasswordReset'
        );

        $events->listen(
            UserCreated::class,
            'Domain\User\Listeners\UserEventListener@onCreated'
        );

        $events->listen(
            UserDeleted::class,
            'Domain\User\Listeners\UserEventListener@onDeleted'
        );

        $events->listen(
            UserUpdated::class,
            'Domain\User\Listeners\UserEventListener@onUpdated'
        );
    }
}

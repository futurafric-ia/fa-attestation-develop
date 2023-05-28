<?php

namespace Domain\Request\Listeners;

use Domain\Request\Events\RequestApproved;
use Domain\Request\Events\RequestCancelled;
use Domain\Request\Events\RequestCreated;
use Domain\Request\Events\RequestDelivered;
use Domain\Request\Events\RequestRejected;
use Domain\Request\Events\RequestUpdated;
use Domain\Request\Events\RequestValidated;
use Domain\Request\Notifications\RequestApprovedNotification;
use Domain\Request\Notifications\RequestCreatedNotification;
use Domain\Request\Notifications\RequestDeliveredNotification;
use Domain\Request\Notifications\RequestRejectedNotification;
use Domain\Request\Notifications\RequestValidatedNotification;
use Domain\User\Models\User;
use Notification;

class RequestEventListener
{
    public function onCreated(RequestCreated $event)
    {
        activity('Demande')
            ->performedOn($event->request)
            ->withProperties([
                'event' => "Demande reçue",
                'request' => [
                    'attestation_type_name' => $event->request->attestationType->name,
                    'broker_name' => $event->request->broker->name,
                ],
            ])
            ->log(":causer.full_name de chez :properties.request.broker_name a émis une demande d'attestations de type :properties.request.attestation_type_name");

        Notification::send(
            User::validatorsFromDepartment($event->request->broker->department_id)->get(),
            new RequestCreatedNotification($event->request)
        );
    }

    public function onUpdated(RequestUpdated $event)
    {
        activity('Demande')
            ->performedOn($event->request)
            ->withProperties([
                'event' => "Demande mise à jour",
                'request' => [
                    'broker_name' => $event->request->broker->name,
                ],
            ])
            ->log(':causer.full_name de chez :properties.request.broker_name a mis à jour une demande.');
    }

    public function onApproved(RequestApproved $event)
    {
        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $impersonator = User::find($manager->getImpersonatorId());
            $loggedInUser = auth()->user()->full_name;

            activity('Demande')
                ->performedOn($event->request)
                ->withProperties(['event' => "Demande en cours de traitement"])
                ->log("{$impersonator->full_name} a approuvé une demande d'attestations de type {$event->request->attestationType->name} de l'intermédiaire {$event->request->broker->name} à la place de {$loggedInUser}");
        } else {
            activity('Demande')
                ->performedOn($event->request)
                ->withProperties([
                    'event' => "Demande en cours de traitement",
                    'request' => [
                        'attestation_type_name' => $event->request->attestationType->name,
                        'broker_name' => $event->request->broker->name,
                    ],
                ])
                ->log(":causer.full_name a approuvé une demande d'attestations de type :properties.request.attestation_type_name de l'intermédiaire :properties.request.broker_name");
        }

        Notification::send(
            User::supervisors()->get(),
            new RequestApprovedNotification($event->request)
        );
    }

    public function onValidated(RequestValidated $event)
    {
        $event->request->generateRelatedInquiry();

        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $impersonator = User::find($manager->getImpersonatorId());
            $loggedInUser = auth()->user()->full_name;

            activity('Demande')
                ->performedOn($event->request)
                ->withProperties(['event' => "Demande en cours de traitement"])
                ->log("{$impersonator->full_name} a validé une demande d'attestations de type {$event->request->attestationType->name} de l'intermédiaire {$event->request->broker->name} à la place de {$loggedInUser}");
        } else {
            activity('Demande')
                ->performedOn($event->request)
                ->withProperties([
                    'event' => "Demande validée",
                    'request' => [
                        'attestation_type_name' => $event->request->attestationType->name,
                        'broker_name' => $event->request->broker->name,
                    ],
                ])
                ->log(":causer.full_name a validé une demande d'attestations de type :properties.request.attestation_type_name de l'intermédiaire :properties.request.broker_name");
        }

        Notification::send(
            User::managers()->get(),
            new RequestValidatedNotification($event->request)
        );
    }

    public function onDelivered(RequestDelivered $event)
    {
        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $impersonator = User::find($manager->getImpersonatorId());
            $loggedInUser = auth()->user()->full_name;

            activity('Demande')
                ->performedOn($event->request)
                ->withProperties(['event' => "Demande en cours de traitement"])
                ->log("{$impersonator->full_name} a fait une livraison d'attestations de type {$event->request->attestationType->name} de l'intermédiaire {$event->request->broker->name} à la place de {$loggedInUser}");
        } else {
            activity('Demande')
                ->performedOn($event->request)
                ->withProperties([
                    'event' => "Demande livrée",
                    'request' => [
                        'attestation_type_name' => $event->request->attestationType->name,
                        'broker_name' => $event->request->broker->name,
                    ],
                ])
                ->log(":causer.full_name a fait une livraison d'attestations de type :properties.request.attestation_type_name à l'intermédiaire :properties.request.broker_name");
        }

        Notification::send(
            $event->request->broker->users()->get(),
            new RequestDeliveredNotification($event->request)
        );
    }

    public function onRejected(RequestRejected $event)
    {
        $manager = app('impersonate');

        if ($manager->isImpersonating()) {
            $impersonator = User::find($manager->getImpersonatorId());
            $loggedInUser = auth()->user()->full_name;

            activity('Demande')
                ->performedOn($event->request)
                ->withProperties(['event' => "Demande en cours de traitement"])
                ->log("{$impersonator->full_name} a rejeté une demande d'attestations de type {$event->request->attestationType->name} de l'intermédiaire {$event->request->broker->name} à la place de {$loggedInUser}");
        } else {
            activity('Demande')
                ->performedOn($event->request)
                ->withProperties([
                    'event' => "Demande rejétée",
                    'request' => [
                        'attestation_type_name' => $event->request->attestationType->name,
                        'broker_name' => $event->request->broker->name,
                    ],
                ])
                ->log(":causer.full_name a rejeté une demande d'attestations de type :properties.request.attestation_type_name de l'intermédiaire :properties.request.broker_name");
        }

        Notification::send(
            $event->request->broker->users()->get(),
            new RequestRejectedNotification($event->request)
        );
    }

    public function onCancelled(RequestCancelled $event)
    {
        activity('Demande')
            ->performedOn($event->request)
            ->withProperties([
                'event' => "Demande annulée",
                'request' => [
                    'attestation_type_name' => $event->request->attestationType->name,
                    'broker_name' => $event->request->broker->name,
                ],
            ])
            ->log(":causer.full_name a annulé une demande de type :properties.request.attestation_type_name de l'intermédiaire :properties.request.broker_name");
    }

    public function subscribe($events)
    {
        $events->listen(
            RequestCreated::class,
            'Domain\Request\Listeners\RequestEventListener@onCreated'
        );

        $events->listen(
            RequestUpdated::class,
            'Domain\Request\Listeners\RequestEventListener@onUpdated'
        );

        $events->listen(
            RequestApproved::class,
            'Domain\Request\Listeners\RequestEventListener@onApproved'
        );

        $events->listen(
            RequestValidated::class,
            'Domain\Request\Listeners\RequestEventListener@onValidated'
        );

        $events->listen(
            RequestCancelled::class,
            'Domain\Request\Listeners\RequestEventListener@onCancelled'
        );

        $events->listen(
            RequestRejected::class,
            'Domain\Request\Listeners\RequestEventListener@onRejected'
        );

        $events->listen(
            RequestDelivered::class,
            'Domain\Request\Listeners\RequestEventListener@onDelivered'
        );
    }
}

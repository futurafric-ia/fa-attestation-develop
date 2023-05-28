<?php

namespace Domain\User\Notifications;

use Illuminate\Foundation\Auth\User;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Spatie\WelcomeNotification\WelcomeNotification as SpatieWelcomeNotification;

/**
 * Class WelcomeNotification.
 */
class WelcomeNotification extends SpatieWelcomeNotification
{
    public function buildWelcomeNotificationMessage(): MailMessage
    {
        return (new MailMessage())
            ->subject(sprintf('Bienvenue sur %s', config('app.name')))
            ->greeting(sprintf('Bonjour %s', $this->user->full_name))
            ->line(
                sprintf(
                    'Vous recevez cet email parce que votre compte à été créé sur la plateforme %s. Il ne vous reste plus qu\'a créer votre mot de passe pour vous connecter à la plateforme.',
                    config('app.name')
                )
            )
            ->action('Créer mon mot de passe', $this->showWelcomeFormUrl)
            ->line(sprintf('Ce lien expirera dans %s jours.', $this->validUntil->diffInDays()))
            ->salutation("Cordialement, FA E-Attestation.");
    }

    protected function initializeNotificationProperties(User $user)
    {
        $this->user = $user;
        $this->user->welcome_valid_until = $this->validUntil;
        $this->user->save();

        $this->showWelcomeFormUrl = URL::temporarySignedRoute(
            'welcome',
            $this->validUntil,
            ['user' => $user->getRouteKey()]
        );
    }
}

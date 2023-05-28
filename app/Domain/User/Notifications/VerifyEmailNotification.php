<?php

namespace Domain\User\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

/**
 * Class VerifyEmailNotification.
 */
class VerifyEmailNotification extends Notification
{
    /**
     * Get the notification's channels.
     *
     * @param mixed $notifiable
     *
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Build the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage())
            ->subject(__('Verification de l\'adresse E-mail'))
            ->greeting(sprintf('Bonjour %s', $notifiable->full_name))
            ->line(__('Veillez cliquer sur le lien ci-dessous pour verifier votre adresse E-mail.'))
            ->action(__('Verifer mon adresse E-mail'), $this->verificationUrl($notifiable))
            ->salutation("Cordialement, FA E-Attestation.");
    }

    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     *
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}

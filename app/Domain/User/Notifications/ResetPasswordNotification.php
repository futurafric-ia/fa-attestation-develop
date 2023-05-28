<?php

namespace Domain\User\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class ResetPasswordNotification.
 */
class ResetPasswordNotification extends Notification
{
    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * Create a notification instance.
     *
     * @param string $token
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

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
            ->subject(__('Réinitialisation de votre mot de passe'))
            ->greeting(sprintf('Bonjour %s', $notifiable->full_name))
            ->line('Vous recevez cet email parce que vous avez demandé la réinitialisation de votre mot de passe.')
            ->action(__('Réinitialiser mon mot de passe'), route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]))
            ->line(__('Ce lien expira dans :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line('Si vous n\'avez pas demandé de réinitialisation de mot de passe, aucune action est requise de votre part.')
            ->salutation("Cordialement, FA E-Attestation.");
    }
}

<?php

namespace Domain\User\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Class ResetPasswordNotification.
 */
class ResetUserPasswordNotification extends Notification
{
    public $newPassword;

    /**
     * Create a notification instance.
     *
     * @param string $newPassword
     */
    public function __construct(string $newPassword)
    {
        $this->newPassword = $newPassword;
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
            ->line('Votre mot de passe à été réinitialisé par l\'administrateur.')
            ->line(sprintf('Votre nouveau mot de passe est: %s', $this->newPassword))
            ->line('Veuillez vous connecter à votre compte et changer de mot de passe par mesure de sécurité.')
            ->action('Se connecter', route('login'))
            ->salutation("Cordialement, FA E-Attestation.");
    }
}

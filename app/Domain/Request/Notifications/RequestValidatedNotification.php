<?php

namespace Domain\Request\Notifications;

use Domain\Request\Models\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestValidatedNotification extends Notification
{
    use Queueable;

    public $request;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'request_validated',
            'request_id' => $this->request->uuid,
            'broker_name' => $this->request->broker->name,
        ];
    }
}

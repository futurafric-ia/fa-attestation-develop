<?php

namespace Domain\Reports\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ExportReady extends Notification
{
    use Queueable;

    protected $fileName;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
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
            'type' => 'export_ready',
            'file_name' => $this->fileName,
        ];
    }
}

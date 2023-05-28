<?php

namespace App\Http\Livewire\Notification;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class NotificationIndicator extends Component
{
    public $hasNotification;

    protected $listeners = [
        'NotificationMarkedAsRead' => 'setHasNotification',
    ];

    public function render()
    {
        $this->hasNotification = $this->setHasNotification(
            Auth::user()->unreadNotifications()->count(),
        );

        return view('livewire.notification.notification-indicator', [
            'hasNotification' => $this->hasNotification,
        ]);
    }

    public function setHasNotification(int $count): bool
    {
        return $count > 0;
    }
}

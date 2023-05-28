<?php

namespace App\Http\Livewire\Notification;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

final class NotificationCount extends Component
{
    public $count;

    protected $listeners = [
        'NotificationMarkedAsRead' => 'updateCount',
    ];

    public function render()
    {
        $this->count = Auth::user()->unreadNotifications()->count();

        return view('livewire.notification.notification-count', [
            'count' => $this->count,
        ]);
    }

    public function updateCount(int $count): int
    {
        return $count;
    }
}

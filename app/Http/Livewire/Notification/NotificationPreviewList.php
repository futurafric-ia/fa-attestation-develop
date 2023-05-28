<?php

namespace App\Http\Livewire\Notification;

use Livewire\Component;

class NotificationPreviewList extends Component
{
    public function render()
    {
        return view('livewire.notification.notification-preview-list', [
            'notifications' => auth()->user()->unreadNotifications()->limit(5)->get(),
        ]);
    }
}

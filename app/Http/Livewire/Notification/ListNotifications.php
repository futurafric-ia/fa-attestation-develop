<?php

namespace App\Http\Livewire\Notification;

use Domain\Logger\Policies\NotificationPolicy;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Notifications\DatabaseNotification;
use Livewire\Component;
use Livewire\WithPagination;

class ListNotifications extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $notificationId;

    public function mount(): void
    {
        abort_if(auth()->guest(), 403);
    }

    public function getNotificationProperty(): DatabaseNotification
    {
        return DatabaseNotification::findOrFail($this->notificationId);
    }

    public function markAsRead(string $notificationId): void
    {
        $this->notificationId = $notificationId;

        $this->authorize(NotificationPolicy::MARK_AS_READ, $this->notification);

        $this->notification->markAsRead();

        $this->emit('NotificationMarkedAsRead', auth()->user()->unreadNotifications()->count());
    }

    public function render()
    {
        return view('livewire.notification.list-notifications', [
            'notifications' => auth()->user()->unreadNotifications()->paginate(10),
        ]);
    }
}

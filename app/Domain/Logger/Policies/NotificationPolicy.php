<?php

namespace Domain\Logger\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Notifications\DatabaseNotification;

class NotificationPolicy
{
    use HandlesAuthorization;

    public const MARK_AS_READ = 'markAsRead';

    /**
     * Determine if the given notification can be marked as read by the user.
     * @param User $user
     * @param DatabaseNotification $notification
     * @return bool
     */
    public function markAsRead(User $user, DatabaseNotification $notification): bool
    {
        return $notification->notifiable->is($user);
    }
}

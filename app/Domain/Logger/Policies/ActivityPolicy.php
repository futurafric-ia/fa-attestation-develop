<?php

namespace Domain\Logger\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ActivityPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->can('activity.list');
    }
}

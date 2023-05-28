<?php

namespace Domain\Delivery\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('delivery.list');
    }

    public function create(User $user)
    {
        return $user->can('delivery.create');
    }
}

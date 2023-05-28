<?php

namespace Domain\Supply\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplyPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('supply.list');
    }

    public function create(User $user)
    {
        return $user->can('supply.create');
    }
}

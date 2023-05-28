<?php

namespace Domain\Broker\Policies;

use Domain\Broker\Models\Broker;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BrokerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if ($user->can('broker.list')) {
            return true;
        }
    }

    public function view(User $user, Broker $broker)
    {
        if ($user->can('broker.list')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->can('broker.create')) {
            return true;
        }
    }

    public function update(User $user, Broker $broker)
    {
        if ($user->can('broker.update')) {
            return true;
        }
    }

    public function delete(User $user, Broker $broker)
    {
        if ($user->can('broker.delete')) {
            return true;
        }
    }
}

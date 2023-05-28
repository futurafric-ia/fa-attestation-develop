<?php

namespace Domain\Request\Policies;

use Domain\Request\Models\Request;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if ($user->can('request.list')) {
            return true;
        }
    }

    public function view(User $user, Request $request)
    {
        return $user->isBroker()
            ? $user->current_broker_id == $request->broker_id && $user->can('request.list')
            : $user->can('request.list');
    }

    public function create(User $user)
    {
        if ($user->can('request.create')) {
            return true;
        }
    }

    public function update(User $user, Request $request)
    {
        if ($user->current_broker_id == $request->broker_id && $user->can('request.update')) {
            return true;
        }
    }

    public function reject(User $user, Request $request)
    {
        if ($user->can('request.reject')) {
            return true;
        }
    }

    public function approve(User $user, Request $request)
    {
        if ($user->can('request.approve')) {
            return true;
        }
    }

    public function validate(User $user, Request $request)
    {
        if ($user->can('request.validate')) {
            return true;
        }
    }

    public function cancel(User $user, Request $request)
    {
        if ($user->current_broker_id == $request->broker_id && $user->can('request.cancel')) {
            return true;
        }
    }
}

<?php

namespace Domain\User\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $loggedInUser)
    {
        if ($loggedInUser->can('user.list')) {
            return true;
        }
    }

    public function view(User $loggedInUser, User $user)
    {
        if ($loggedInUser->can('user.list')) {
            return true;
        }
    }

    public function create(User $loggedInUser)
    {
        if ($loggedInUser->can('user.create')) {
            return true;
        }
    }

    public function update(User $loggedInUser, User $user)
    {
        if ($loggedInUser->can('user.update')) {
            return true;
        }
    }

    public function delete(User $loggedInUser, User $user)
    {
        if ($user->id !== $loggedInUser->id && $loggedInUser->can('user.delete')) {
            return true;
        }
    }

    public function impersonate(User $loggedInUser, User $user)
    {
        if ($user->id !== $loggedInUser->id && $loggedInUser->can('user.impersonate')) {
            return true;
        }
    }

    public function resetPassword(User $loggedInUser, User $user)
    {
        if ($user->id !== $loggedInUser->id && $loggedInUser->can('user.reset_password')) {
            return true;
        }
    }

    public function invite(User $loggedInUser)
    {
        if ($loggedInUser->can('user.invite')) {
            return true;
        }
    }
}

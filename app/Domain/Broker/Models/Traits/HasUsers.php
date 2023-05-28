<?php

namespace Domain\Broker\Models\Traits;

use Domain\User\Models\User;

trait HasUsers
{
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function addUser(User $user)
    {
        $user->attachBroker($this);
    }

    public function removeUser(User $user)
    {
        $user->detachBroker($this);
        $user->to_be_logged_out = true;
        $user->active = false;
        $user->save();
    }

    public function activateUsers()
    {
        $this->users()->update(['active' => true]);
    }

    public function deactivateUsers()
    {
        $this->users()->update(['active' => false, 'to_be_logged_out' => true]);
    }

    /**
     * Helper function to determine if a user is part of this broker.
     *
     * @param Model $user
     * @return bool
     */
    public function hasUser(User $user)
    {
        return $this->users()->where($user->getKeyName(), '=', $user->getKey())->first() ? true : false;
    }
}

<?php

namespace Domain\Broker\Models\Traits;

use Domain\User\Models\User;

trait HasOwner
{
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function setOwner(User $owner)
    {
        if (! $this->users()->find($owner->id)) {
            throw new \RuntimeException("Ce utilisateur n'est pas membre de ce intermédiaire.");
        }

        if ($this->owner_id !== null) {
            $this->owner->syncPermissions([]);
        }

        $owner->syncPermissions(['user.invite']);

        $this->update(['owner_id' => $owner->id]);
    }
}

<?php

namespace Domain\Attestation\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AttestationPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user, ?bool $withAnterior = null)
    {
        if ($withAnterior) {
            return $user->can('attestation.list') && $user->can('attestation.list_anterior');
        }

        return $user->can('attestation.list');
    }

    public function scan(User $user)
    {
        return $user->can('attestation.scan');
    }
}

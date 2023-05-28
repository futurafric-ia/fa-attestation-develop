<?php

namespace Domain\Scan\Policies;

use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScanPolicy
{
    use HandlesAuthorization;

    public function create(User $user)
    {
        if ($user->can('attestation.scan')) {
            return true;
        }
    }
}

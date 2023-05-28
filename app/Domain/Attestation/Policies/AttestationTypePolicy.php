<?php

namespace Domain\Attestation\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class AttestationTypePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}

<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Domain\User\Notifications\ResetUserPasswordNotification;
use Illuminate\Support\Str;

final class ResetUserPasswordAction
{
    public function execute(User $user, ?string $password): User
    {
        $user->update(compact('password'));

        return $user->fresh();
    }
}

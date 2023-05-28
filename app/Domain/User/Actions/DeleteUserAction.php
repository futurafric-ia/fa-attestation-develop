<?php

namespace Domain\User\Actions;

use Domain\User\Events\UserDeleted;
use Domain\User\Models\User;
use Illuminate\Validation\ValidationException;

final class DeleteUserAction
{
    public function execute(User $user): User
    {
        if (null !== $user->deleted_at) {
            throw_if(null !== $user->deleted_at, ValidationException::withMessages([
                'delete_user' => 'Ce utilisateur est deja supprimé.',
            ]));
        }

        $user->delete();

        UserDeleted::dispatch($user);

        return $user;
    }
}

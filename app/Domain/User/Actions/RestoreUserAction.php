<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Illuminate\Validation\ValidationException;

final class RestoreUserAction
{
    public function execute(User $user): User
    {
        if (null === $user->deleted_at) {
            throw_if(null !== $user->deleted_at, ValidationException::withMessages([
                'restore_user' => 'Ce utilisateur n\'est pas supprimé et ne peut donc etre restoré.',
            ]));
        }

        if (! $user->restore()) {
            throw_if(null !== $user->deleted_at, ValidationException::withMessages([
                'restore_user' => 'Une erreur est survenu lors de la restauration de l\'utilisateur.',
            ]));
        }

        return $user;
    }
}

<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

final class UpdatePasswordAction
{
    public function execute(User $user, $data): User
    {
        if (isset($data['current_password'])) {
            throw_if(
                ! Hash::check($data['current_password'], $user->password),
                ValidationException::withMessages(['update_password' => 'Votre mot de passe actuel n\'est pas correct'])
            );
        }

        $user->password = $data['password'];
        $user->password_changed_at = now();
        $user->save();


        return $user;
    }
}

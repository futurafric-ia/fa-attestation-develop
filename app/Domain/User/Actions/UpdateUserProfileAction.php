<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;

final class UpdateUserProfileAction
{
    public function execute(User $user, array $data): User
    {
        $user->first_name = $data['first_name'] ?? $user->first_name;
        $user->last_name = $data['last_name'] ?? $user->last_name;
        $user->contact = $data['contact'] ?? $user->contact;
        $user->address = $data['address'] ?? $user->address;

        if (isset($data['email']) && $user->email !== $data['email']) {
            $user->email = $data['email'];
            $user->email_verified_at = null;
            $user->sendEmailVerificationNotification();
        }

        $user->save();

        return $user->fresh();
    }
}

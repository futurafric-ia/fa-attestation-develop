<?php

namespace Domain\User\Actions;

use Domain\Authorization\Models\Role;
use Domain\User\Events\UserCreated;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

final class CreateUserAction
{
    public function execute(array $data): User
    {
        DB::beginTransaction();

        $user = User::create([
            'email' => $data['email'],
            'last_name' => $data['last_name'],
            'first_name' => $data['first_name'],
            'identifier' => $data['identifier'] ?? null,
            'contact' => $data['contact'] ?? null,
            'address' => $data['address'] ?? null,
            'type' => $data['type'],
            'email_verified_at' => now(),
            'api_token' => Str::random(60),
        ]);

        $user->syncRoles($data['roles'] ?? [Role::BROKER]);
        $user->syncPermissions($data['permissions'] ?? []);

        if (isset($data['department_id']) && null !== $data['department_id']) {
            $user->departments()->attach($data['department_id']);
        }

        DB::commit();

        UserCreated::dispatch($user);

        $user->sendWelcomeNotification(now()->addWeek());

        return $user;
    }
}

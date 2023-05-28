<?php

namespace Domain\User\Actions;

use Domain\User\Events\UserUpdated;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

final class UpdateUserAction
{
    public function execute(User $user, array $data)
    {
        DB::beginTransaction();

        $user->update([
            'email' => $data['email'] ?? $user->email,
            'last_name' => $data['last_name'] ?? $user->last_name,
            'first_name' => $data['first_name'] ?? $user->first_name,
            'identifier' => $data['identifier'] ?? $user->identifier,
            'contact' => $data['contact'] ?? $user->contact,
            'address' => $data['address'] ?? $user->address,
        ]);

        if (isset($data['department_id']) && null !== $data['department_id']) {
            $user->departments()->detach();
            $user->departments()->attach($data['department_id']);
        }

        if (! $user->isMasterAdmin()) {
            $user->syncRoles($data['roles'] ?? []);
            $user->syncPermissions($data['permissions'] ?? []);
        }

        DB::commit();

        UserUpdated::dispatch($user);

        return $user->fresh();
    }
}

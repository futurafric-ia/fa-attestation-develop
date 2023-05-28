<?php

namespace Domain\Broker\Actions;

use DB;
use Domain\Broker\Events\BrokerRestored;
use Domain\Broker\Models\Broker;
use Domain\User\Models\User;
use Illuminate\Validation\ValidationException;

final class RestoreBrokerAction
{
    public function execute(Broker $broker): Broker
    {
        if (null === $broker->deleted_at) {
            throw_if(null !== $user->deleted_at, ValidationException::withMessages([
                'delete_user' => 'Vous ne pouvez restauré un intermédiaire non supprimé.',
            ]));
        }

        DB::transaction(function () use ($broker) {
            $broker->restore();
            $broker->users()->withTrashed()->get()->each(function (User $user) {
                $user->restore();
            });
        });

        BrokerRestored::dispatch($broker);

        return $broker;
    }
}

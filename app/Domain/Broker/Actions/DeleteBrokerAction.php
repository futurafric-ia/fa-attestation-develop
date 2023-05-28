<?php

namespace Domain\Broker\Actions;

use DB;
use Domain\Broker\Events\BrokerDeleted;
use Domain\Broker\Models\Broker;
use Illuminate\Validation\ValidationException;

final class DeleteBrokerAction
{
    public function execute(Broker $broker): Broker
    {
        if (null !== $broker->deleted_at) {
            throw_if(null !== $broker->deleted_at, ValidationException::withMessages([
                'delete_user' => 'Ce intermédiaire est deja supprimé.',
            ]));
        }

        DB::transaction(function () use ($broker) {
            $broker->users()->delete();
            $broker->delete();
        });

        event(new BrokerDeleted($broker));

        return $broker;
    }
}

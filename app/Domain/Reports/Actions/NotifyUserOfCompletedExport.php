<?php

namespace Domain\Reports\Actions;

use Domain\Reports\Notifications\ExportReady;
use Domain\User\Models\User;
use Spatie\QueueableAction\QueueableAction;

class NotifyUserOfCompletedExport
{
    use QueueableAction;

    public function execute(User $user, string $fileName)
    {
        $user->notify(new ExportReady($fileName));
    }
}

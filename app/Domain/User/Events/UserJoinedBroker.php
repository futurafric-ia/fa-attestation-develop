<?php

namespace Domain\User\Events;

use Domain\User\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserJoinedBroker
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * @var
     */
    public User $user;

    protected $brokerId;

    /**
     * @param $user
     */
    public function __construct(User $user, $brokerId)
    {
        $this->user = $user;
        $this->brokerId = $brokerId;
    }

    /**
    * @return Model
    */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return int
     */
    public function getBrokerId()
    {
        return $this->brokerId;
    }
}

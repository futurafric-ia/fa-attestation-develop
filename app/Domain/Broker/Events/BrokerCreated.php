<?php

namespace Domain\Broker\Events;

use Domain\Broker\Models\Broker;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BrokerCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Broker $broker;

    /**
     * @param Broker $broker
     */
    public function __construct(Broker $broker)
    {
        $this->broker = $broker;
    }
}

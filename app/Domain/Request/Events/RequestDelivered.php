<?php

namespace Domain\Request\Events;

use Domain\Delivery\Models\Delivery;
use Domain\Request\Models\Request;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestDelivered
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Request $request;

    private Delivery $delivery;

    public function __construct(Request $request, Delivery $delivery)
    {
        $this->request = $request;
        $this->delivery = $delivery;
    }
}

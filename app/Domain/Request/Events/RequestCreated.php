<?php

namespace Domain\Request\Events;

use Domain\Request\Models\Request;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RequestCreated
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Request $request;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
}

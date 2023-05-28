<?php

namespace Domain\Delivery\Events;

use Domain\Delivery\Models\Delivery;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryFailed
{
    use Dispatchable;
    use SerializesModels;

    public Delivery $delivery;
    public string $reason;

    public function __construct(Delivery $delivery, string $reason)
    {
        $this->delivery = $delivery;
        $this->reason = $reason;
    }
}

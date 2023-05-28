<?php

namespace Domain\Delivery\Events;

use Domain\Delivery\Models\Delivery;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeliveryDone
{
    use Dispatchable;
    use SerializesModels;

    public Delivery $delivery;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Delivery $delivery)
    {
        $this->delivery = $delivery;
    }
}

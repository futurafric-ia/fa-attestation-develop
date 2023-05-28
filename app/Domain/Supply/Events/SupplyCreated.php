<?php

namespace Domain\Supply\Events;

use Domain\Supply\Models\Supply;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplyCreated
{
    use Dispatchable;
    use SerializesModels;

    public Supply $supply;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Supply $supply)
    {
        $this->supply = $supply;
    }
}

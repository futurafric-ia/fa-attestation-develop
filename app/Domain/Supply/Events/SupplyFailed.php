<?php

namespace Domain\Supply\Events;

use Domain\Supply\Models\Supply;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplyFailed
{
    use Dispatchable;
    use SerializesModels;

    public Supply $supply;
    public string $reason;

    /**
     * Create a new event instance.
     *
     * @param Supply $supply
     * @param string $reason
     */
    public function __construct(Supply $supply, string $reason)
    {
        $this->supply = $supply;
    }
}

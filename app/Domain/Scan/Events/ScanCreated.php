<?php

namespace Domain\Scan\Events;

use Domain\Scan\Models\Scan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScanCreated
{
    use Dispatchable;
    use SerializesModels;

    public $scan;

    /**
     * Create a new event instance.
     *
     * @param Scan $scan
     */
    public function __construct(Scan $scan)
    {
        $this->scan = $scan;
    }
}

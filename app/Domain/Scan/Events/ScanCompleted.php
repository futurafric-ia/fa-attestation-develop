<?php

namespace Domain\Scan\Events;

use Domain\Scan\Models\Scan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScanCompleted
{
    use Dispatchable;
    use SerializesModels;

    public $scan;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Scan $scan)
    {
        $this->scan = $scan;
    }
}

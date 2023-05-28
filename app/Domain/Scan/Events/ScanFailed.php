<?php

namespace Domain\Scan\Events;

use Domain\Scan\Models\Scan;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScanFailed
{
    use Dispatchable;
    use SerializesModels;

    public $scan;

    public $reason;

    public function __construct(Scan $scan, string $reason)
    {
        $this->scan = $scan;
        $this->reason = $reason;
    }
}

<?php

namespace Domain\Supply\Events;

use Domain\Supply\Models\Supplier;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SupplierDeleted
{
    use Dispatchable;
    use SerializesModels;

    public Supplier $supplier;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Supplier $supplier)
    {
        $this->supplier = $supplier;
    }
}

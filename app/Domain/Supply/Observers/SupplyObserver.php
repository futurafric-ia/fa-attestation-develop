<?php

namespace Domain\Supply\Observers;

use Domain\Supply\Models\Supply;

class SupplyObserver
{
    public function creating(Supply $supply)
    {
        $supply->code = generateDateId('supplies', 'code', 'APPR');
    }
}

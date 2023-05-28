<?php

namespace App\Http\Livewire\Request;

use Domain\Analytics\Analytics;
use Livewire\Component;

class ShowRequestsStats extends Component
{
    public function render()
    {
        $analytics = new Analytics();

        return view('livewire.request.show-requests-stats', [
            'totalRequestDeliveredCount' => $analytics->totalDeliveredRequestCount(),
            'totalRequestValidatedCount' => $analytics->totalValidatedRequestCount()
        ]);
    }
}

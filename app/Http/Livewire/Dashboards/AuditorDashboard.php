<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Analytics\Analytics;
use Domain\Authorization\Models\Role;
use Livewire\Component;

class AuditorDashboard extends Component
{
    public function mount()
    {
        abort_if(! auth()->user()->hasRole(Role::AUDITOR), 403);
    }

    public function render()
    {
        $analytics = new Analytics();

        return view('livewire.dashboards.auditor-dashboard', [
            'totalBrokerCount' => $analytics->totalBrokerCount(),
            'totalUserCount' => $analytics->totalUserCount()
        ]);
    }
}

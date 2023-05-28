<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Analytics\Analytics;
use Domain\Authorization\Models\Role;
use Domain\Logger\Models\Activity;
use Livewire\Component;

class AdminDashboard extends Component
{
    public function mount()
    {
        abort_if(! auth()->user()->hasRole(Role::ADMIN), 403);
    }

    public function render()
    {
        $analytics = new Analytics();
        $latestActivities = Activity::query()->where('log_name', '!=', 'attestation')->limit(5)->latest()->get();

        return view('livewire.dashboards.admin-dashboard', [
            'totalUserCount' => $analytics->totalUserCount(),
            'totalBrokerCount' => $analytics->totalBrokerCount(),
            'totalAttestationCount' => $analytics->totalAttestationCount(),
            'totalSupplierCount' => $analytics->totalSupplierCount(),
            'latestActivities' => $latestActivities
        ]);
    }
}

<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Analytics\Analytics;
use Domain\Authorization\Models\Role;
use Domain\Request\Models\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class BrokerDashboard extends Component
{
    public function mount()
    {
        abort_if(! auth()->user()->hasRole(Role::BROKER), 403);
    }

    public function render()
    {
        $broker = broker();
        $analytics = new Analytics();

        $totalRequestCountByState = Request::select('state', DB::raw('count(*) as total'))
            ->allowedForUser(auth()->user())
            ->where('broker_id', $broker->id)
            ->groupBy('state')
            ->pluck('total', 'state');

        return view('livewire.dashboards.broker-dashboard', [
            'totalRequestCountByState' => $totalRequestCountByState,
            'totalBrokerUsersCount' => $analytics->totalBrokerUsersCount($broker)
        ]);
    }
}

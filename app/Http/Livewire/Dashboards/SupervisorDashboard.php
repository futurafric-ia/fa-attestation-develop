<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Analytics\Analytics;
use Domain\Authorization\Models\Role;
use Domain\Request\Models\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SupervisorDashboard extends Component
{
    public function mount()
    {
        abort_if(! auth()->user()->hasRole(Role::SUPERVISOR), 403);
    }

    public function render()
    {
        $analytics = new Analytics();

        $result = Request::select(['state', DB::raw('count(*) as total')])
            ->onlyParent()
            ->allowedForUser(auth()->user())
            ->groupBy('state')
            ->pluck('total', 'state');

        $result[\Domain\Request\States\Validated::$name] = (
            ($result[\Domain\Request\States\Validated::$name] ?? 0) +
            ($result[\Domain\Request\States\Delivered::$name] ?? 0)
        );

        return view('livewire.dashboards.supervisor-dashboard', [
            'totalAvailableStock' => $analytics->totalAvailableStock(),
            'totalRequestCountByState' => $result,
        ]);
    }
}

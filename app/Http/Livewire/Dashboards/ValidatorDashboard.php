<?php

namespace App\Http\Livewire\Dashboards;

use App\ViewModels\Analytics\ValidatorAnalyticsViewModel;
use Domain\Authorization\Models\Role;
use Domain\Request\Models\Request;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ValidatorDashboard extends Component
{
    public function mount()
    {
        abort_if(! auth()->user()->hasRole(Role::VALIDATOR), 403);
    }

    public function render()
    {
        $result = Request::select(['state', DB::raw('count(*) as total')])
            ->onlyParent()
            ->allowedForUser(auth()->user())
            ->groupBy('state')
            ->pluck('total', 'state');

        $result[\Domain\Request\States\Approved::$name] = (
            ($result[\Domain\Request\States\Approved::$name] ?? 0) +
            ($result[\Domain\Request\States\Delivered::$name] ?? 0) +
            ($result[\Domain\Request\States\Validated::$name] ?? 0)
        );

        return view('livewire.dashboards.validator-dashboard', [
            'totalRequestCountByState' => $result
        ]);
    }
}

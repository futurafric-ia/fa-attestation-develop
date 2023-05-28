<?php

namespace App\Http\Controllers\API;

use DB;
use Domain\Broker\Models\Broker;
use Domain\Request\Models\Request;
use Domain\Request\States\Approved;
use Domain\Request\States\Cancelled;
use Domain\Request\States\Delivered;
use Domain\Request\States\Pending;
use Domain\Request\States\Rejected;
use Domain\Request\States\Validated;
use Domain\User\Models\User;

class BrokerDashboardController
{
    public function index(Broker $broker)
    {
        $query = Request::select(['state', DB::raw('count(*) as total')])
            ->onlyParent()
            ->where('broker_id', $broker->id)
            ->groupBy('state');

        $totalRequestByState = $query->pluck('total', 'state');

        foreach ([Pending::$name, Cancelled::$name, Approved::$name, Validated::$name, Delivered::$name, Rejected::$name] as $key) {
            $totalRequestByState[$key] = $totalRequestByState[$key] ?? 0;
        }

        $totalBrokerUsersCount = User::query()
            ->onlyBrokers()
            ->whereHas('currentBroker', fn ($builder) => $builder->where('id', $broker->id))
            ->count();

        return response()->json([
            'data' => [
                'demandes' => $totalRequestByState,
                'total_utilisateurs' => $totalBrokerUsersCount,
            ],
        ]);
    }
}

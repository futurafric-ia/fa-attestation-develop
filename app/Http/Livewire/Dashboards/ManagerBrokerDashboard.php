<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Attestation\Models\Attestation;
use Domain\Broker\Models\Broker;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ManagerBrokerDashboard extends Component
{
    public $broker;

    protected $listeners = [
        'broker_idUpdated' => 'setBroker',
    ];

    public function setBroker($payload)
    {
        $this->broker = Broker::find($payload['value']);
    }

    public function clearBrokerSelection()
    {
        $this->broker = null;

        $this->emit('clearBrokerSelection');
    }

    public function render()
    {
        $totalAvailableStockByState = Attestation::select('state', DB::raw('count(*) as total'))
            ->groupBy('state')
            ->when($this->broker, function ($query) {
                return $query->where('current_broker_id', $this->broker->id);
            })
            ->pluck('total', 'state');

        return view('livewire.dashboards.manager-broker-dashboard', [
            'totalAvailableStockByState' => $totalAvailableStockByState,
            'brokers' => Broker::query()->limit(25)->pluck('name', 'id')->toArray()
        ]);
    }
}

<?php

namespace App\Http\Livewire\Broker;

use Domain\Analytics\Analytics;
use Domain\Broker\Actions\UpdateBrokerAction;
use Domain\Broker\Models\Broker;
use Livewire\Component;

class ShowBroker extends Component
{
    public $broker;

    public $authorizeRequest = true;

    public $enableConsomptionThreshold = true;

    public $state = [
        'notes' => null,
        'minimum_consumption_percentage' => null,
    ];

    public function mount(Broker $broker)
    {
        $this->broker = $broker;
        $this->enableConsomptionThreshold = $this->broker->minimum_consumption_percentage !== 0;
        $this->authorizeRequest = $this->broker->active;
        $this->state = [
            'minimum_consumption_percentage' => $this->broker->minimum_consumption_percentage,
            'notes' => $this->broker->notes,
        ];
    }

    public function updateAuthorization(UpdateBrokerAction $updateBrokerAction)
    {
        $consomptionThreshold = $this->enableConsomptionThreshold ? $this->state['minimum_consumption_percentage'] : 0;

        $updateBrokerAction->execute($this->broker, [
            'active' => $this->authorizeRequest,
            'minimum_consumption_percentage' => $consomptionThreshold,
            'notes' => $this->state['notes'],
        ]);

        $this->state['minimum_consumption_percentage'] = $consomptionThreshold;

        $this->emit('authorizationSaved');
    }

    public function render()
    {
        $analytics = new Analytics();

        return view('livewire.broker.show-broker', [
            'totalRequestDeliveredCount' => $analytics->totalDeliveredRequestCount($this->broker),
            'totalRequestCount' => $analytics->totalRequestCountForBroker($this->broker),
            'latestDeliveries' => $this->broker->deliveries()->with('attestationType')->limit(5)->latest()->get(),
            'department' => $this->broker->department,
            'broker' => $this->broker
        ]);
    }
}

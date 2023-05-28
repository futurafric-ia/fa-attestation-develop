<?php

namespace App\Http\Livewire\Delivery;

use Domain\Delivery\Models\Delivery;
use Livewire\Component;

class DeliveryStats extends Component
{
    public $selectedDelivery;

    public $showingDeliveryAttestations = false;

    protected $listeners = [
        'show-delivery-attestations' => 'showDeliveryAttestations',
    ];

    public function applyFilter($filters)
    {
        $this->emit('set-advanced-filters', $filters);
    }

    public function showDeliveryAttestations($deliveryId)
    {
        $this->showingDeliveryAttestations = true;
        $this->selectedDelivery = Delivery::whereId($deliveryId)->with('items')->first();
    }

    public function close()
    {
        $this->showingDeliveryAttestations = false;
        $this->selectedDelivery = null;
    }

    public function render()
    {
        return view('livewire.delivery.delivery-stats');
    }
}

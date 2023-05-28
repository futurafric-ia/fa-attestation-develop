<?php

namespace App\Http\Livewire\Delivery;

use Domain\Delivery\Models\Delivery;
use Domain\Delivery\States\Done;
use Illuminate\Http\Request;
use Livewire\Component;

class ShowDeliveryInvoice extends Component
{
    public $selectedDelivery = null;

    public $searchTerm = null;

    public $deliveries = null;

    protected $queryString = ['searchTerm'];

    public function mount(Request $request, Delivery $delivery)
    {
        $this->selectedDelivery = $delivery;
        $this->deliveries = Delivery::query()
            ->with('broker')
            ->when($request->query('searchTerm'), function ($query) use ($request) {
                $query->whereHas('broker', function ($q) use ($request) {
                    $q->where('code', 'like', "%" . $request->query('searchTerm') . "%")->orWhere('name', 'like', "%" . $request->query('searchTerm') . "%");
                })->orWhere('code', 'like', "%" . $request->query('searchTerm') . "%");
            })
            ->allowedForUser(auth()->user())
            ->whereState('state', Done::class)
            ->latest()
            ->limit(25)
            ->get();
    }

    public function setSelectedInvoice($id)
    {
        $this->selectedDelivery = Delivery::find($id);
    }

    public function search()
    {
        $this->deliveries = Delivery::query()
            ->with('broker')
            ->allowedForUser(auth()->user())
            ->when($this->searchTerm, function ($query) {
                $query->whereHas('broker', function ($q) {
                    $q->where('code', 'like', "%" . $this->searchTerm . "%")->orWhere('name', 'like', "%" . $this->searchTerm . "%");
                })->orWhere('code', 'like', "%" . $this->searchTerm . "%");
                // $query->where('code', 'like', "%".$this->searchTerm."%");
            })
            ->whereState('state', Done::class)
            ->latest()
            ->get();

        $this->selectedDelivery = $this->deliveries->first();
    }

    public function render()
    {
        return view('livewire.delivery.show-delivery-invoice');
    }
}

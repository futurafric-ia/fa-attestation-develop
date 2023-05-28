<?php

namespace App\Http\Livewire\Supplier;

use Domain\Analytics\Analytics;
use Domain\Supply\Models\Supplier;
use Livewire\Component;

class ShowSupplier extends Component
{
    public Supplier $supplier;

    public function render()
    {
        $analytics = new Analytics();

        return view('livewire.supplier.show-supplier', [
            'supplier' => $this->supplier,
            'latestSupplies' => $this->supplier->supplies()->with('attestationType')->latest()->limit(5)->get(),
            'totalSupplyCount' => $analytics->totalSupplyCount($this->supplier),
            'totalAvailableAttestationCount' => $analytics->totalAvailableStockForSupplier($this->supplier)
        ]);
    }
}

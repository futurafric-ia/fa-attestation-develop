<?php

namespace App\Http\Livewire\Supply;

use App\ViewModels\Supply\SupplyStockFormViewModel;
use Domain\Supply\Actions\CreateSupplyAction;
use Domain\Supply\Models\Supply;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class SupplyStock extends Component
{
    use AuthorizesRequests;

    public $state = [
        'supplier_id' => null,
        'attestation_type_id' => null,
        'received_at' => null,
        'range_start' => null,
        'range_end' => null,
    ];

    public function supplyStock(CreateSupplyAction $action)
    {
        $this->authorize('create', Supply::class);

        $this->resetErrorBag();

        $this->validate([
            'state.supplier_id' => ['required'],
            'state.attestation_type_id' => ['required'],
            'state.received_at' => ['required', 'date', 'before_or_equal:' . date('Y-m-d')],
            'state.range_start' => ['required', 'integer', 'min:0'],
            'state.range_end' => ['required', 'integer', 'min:0'],
        ]);

        $action->execute($this->state);

        session()->flash('success', "La demande d'approvisionnement a été prise en compte.");

        return redirect()->route('supply.index');
    }

    public function render()
    {
        return view('livewire.supply.supply-stock', new SupplyStockFormViewModel());
    }
}

<?php

namespace App\Http\Livewire\Request;

use App\ViewModels\Request\RequestValidationFormViewModel;
use Domain\Delivery\Actions\CreateDeliveryAction;
use Domain\Delivery\Models\Delivery;
use Domain\Request\Models\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ShowDeliveryForm extends Component
{
    use AuthorizesRequests;

    public Request $request;

    public $steps = 1;

    public $step = 1;

    public $state = [
        'main' => [
            'ranges' => [],
            'quantity' => 0,
        ],
        'related' => [
            'ranges' => [],
            'quantity' => null,
        ],
    ];

    protected $listeners = ['rangesUpdated' => 'setRanges'];

    public function mount(Request $request)
    {
        $this->request = $request->load('child', 'parent');
        $this->steps = $this->request->isRelated() ? 2 : 1;
    }

    public function setRanges(array $items, int $quantity)
    {
        if ($this->step === 1) {
            $this->state['main']['ranges'] = $items;
            $this->state['main']['quantity'] = $quantity;
        } elseif ($this->step === 2) {
            $this->state['related']['ranges'] = $items;
            $this->state['related']['quantity'] = $quantity;
        }
    }

    public function deliver(CreateDeliveryAction $action)
    {
        $this->authorize('create', Delivery::class);

        $this->validate([
            'state.main.quantity' => ['integer', 'size:' . $this->request->quantity_validated],
        ], [
            'state.main.quantity.integer' => "Les numéros d'attestation doivent être des nombres entier",
            'state.main.quantity.size' => "La quantité d'attestations doit être égale à " . $this->request->quantity_validated,
        ]);

        if ($this->request->isRelated()) {
            $this->validate([
                'state.related.quantity' => ['integer', 'size:' . $this->request->quantity_validated],
            ], [
                'state.related.quantity.integer' => "Les numéros d'attestation de la demande associée doivent être des nombres entier",
                'state.related.quantity.size' => "La quantité d'attestations de la demande associée doit être égale à " . $this->request->quantity_validated,
            ]);
        }

        $action->execute($this->request, array_merge($this->state, ['delivered_by' => auth()->id()]));

        session()->flash('success', 'La demande de livraison à été prise en compte');

        return redirect()->route('delivery.index');
    }

    public function render()
    {
        return view('livewire.request.show-delivery-form', new RequestValidationFormViewModel($this->request));
    }
}

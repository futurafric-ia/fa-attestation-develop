<?php

namespace App\Http\Livewire\Scan;

use App\Domain\Scan\Actions\ValidateManualScanAction;
use App\ViewModels\Scan\ScanFormViewModel;
use Domain\Attestation\States\Cancelled;
use Domain\Attestation\States\Returned;
use Domain\Attestation\States\Used;
use Domain\Scan\Actions\CreateScanAction;
use Domain\Scan\Models\Scan;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ManualScan extends Component
{
    use AuthorizesRequests;

    public $items = [];
    public $quantity = 0;
    public $state = [
        'broker_id' => null,
        'attestation_type_id' => null,
        'attestation_state' => null,
    ];

    protected $listeners = [
        'itemsUpdated' => 'setItems',
        'rangesUpdated' => 'setItems',
        'broker_idUpdated' => 'setBroker',
    ];

    public function setBroker($brokerId)
    {
        $this->state['broker_id'] = $brokerId;
    }

    public function setItems(array $items, int $quantity = 0)
    {
        $this->items = $items;

        /**
         * Dans le cas d'une plage attestation, la quantité nous est passé
         * par le composant en charge des plages d'attestations.
         *
         * Dans le cas d'une saisie depuis le formulaire d'attestation,
         * nous pouvons directement calculer la quantité en se basant sur le nombre
         * d'éléments renseignés.
         */
        $this->quantity = $quantity ?: count($this->items);
    }

    public function runScan(CreateScanAction $action, ValidateManualScanAction $validator)
    {
        $this->authorize('create', Scan::class);

        $this->resetErrorBag();

        $this->validate([
            'state.attestation_type_id' => ['required'],
            'state.broker_id' => ['required'],
            'state.attestation_state' => ['sometimes', Rule::in([Returned::$name, Cancelled::$name, Used::$name])],
        ]);

        throw_if(count($this->items) === 0, ValidationException::withMessages([
            'attestation_range' => "Vous n'avez pas renseigné d'attestations.",
        ]));

        $validator->execute(array_merge($this->state, ['items' => $this->items]));

        $action->execute(array_merge($this->state, [
            'type' => Scan::TYPE_MANUAL,
            'initiated_by' => auth()->id(),
            'items' => $this->items,
            'quantity' => $this->quantity,
        ]));

        session()->flash('success', "L'opération a été démarrée avec succès !");

        return redirect()->route('scan.index');
    }

    public function render()
    {
        return view('livewire.scan.manual-scan', new ScanFormViewModel(Scan::TYPE_MANUAL, auth()->user()));
    }
}

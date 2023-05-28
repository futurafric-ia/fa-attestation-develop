<?php

namespace App\Http\Livewire\Request;

use App\ViewModels\Request\RequestFormViewModel;
use Domain\Attestation\Models\AttestationType;
use Domain\Request\Actions\UpdateRequestAction;
use Domain\Request\Events\RequestUpdated;
use Domain\Request\Models\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EditRequestForm extends Component
{
    use AuthorizesRequests;

    public $request;

    public $state = [
        'quantity' => null,
        'notes' => null,
        'attestation_type_id' => null,
        'expected_at' => null,
    ];

    public function mount(Request $request)
    {
        $this->request = $request;
        $this->state = [
            'quantity' => (int) $this->request->quantity,
            'notes' => $this->request->notes,
            'attestation_type_id' => (int) $this->request->attestation_type_id,
            'expected_at' => $this->request->expected_at->format('Y-m-d'),
        ];
    }

    public function saveRequest(UpdateRequestAction $updateRequestAction)
    {
        $this->authorize('update', $this->request);

        $this->validate([
            'state.quantity' => ['required', 'integer', 'min:1'],
            'state.attestation_type_id' => ['required', Rule::in(AttestationType::requestable()->pluck('id'))],
            'state.expected_at' => ['nullable', 'after_or_equal:' . $this->state['expected_at']],
            'state.notes' => ['nullable', 'string', 'max:255'],
        ]);

        RequestUpdated::dispatch($updateRequestAction->execute($this->request, $this->state));

        session()->flash('success', 'La demande à été mise à jour avec succès.');

        return redirect()->route('request.index');
    }

    public function render()
    {
        return view('livewire.request.edit-request-form', new RequestFormViewModel($this->request));
    }
}

<?php

namespace App\Http\Livewire\Request;

use App\ViewModels\Request\RequestFormViewModel;
use Domain\Attestation\Models\AttestationType;
use Domain\Request\Actions\CreateRequestAction;
use Domain\Request\Events\RequestCreated;
use Domain\Request\Models\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateRequestForm extends Component
{
    use AuthorizesRequests;

    public $state = [
        'quantity' => null,
        'notes' => null,
        'attestation_type_id' => null,
        'expected_at' => null,
    ];

    public function saveRequest(CreateRequestAction $createRequestAction)
    {
        $this->authorize('create', Request::class);

        $this->validate([
            'state.quantity' => ['required', 'integer', 'min:1'],
            'state.attestation_type_id' => ['required', Rule::in(AttestationType::requestable()->pluck('id'))],
            'state.expected_at' => ['nullable', 'after:' . date('Y-m-d')],
            'state.notes' => ['nullable', 'string', 'max:255'],
        ]);

        $request = $createRequestAction->execute(array_merge($this->state, [
            'broker_id' => broker()->id,
            'created_by' => auth()->id(),
        ]));

        RequestCreated::dispatch($request);

        session()->flash('success', 'La demande à été soumise avec succès.');

        return redirect()->route('request.show', $request);
    }

    public function render()
    {
        return view('livewire.request.create-request-form', new RequestFormViewModel());
    }
}

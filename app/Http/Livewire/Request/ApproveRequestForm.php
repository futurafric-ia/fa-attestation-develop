<?php

namespace App\Http\Livewire\Request;

use App\ViewModels\Request\RequestValidationFormViewModel;
use Domain\Request\Actions\UpdateRequestAction;
use Domain\Request\Events\RequestApproved;
use Domain\Request\Events\RequestRejected;
use Domain\Request\Models\Request;
use Domain\Request\States\Approved;
use Domain\Request\States\Rejected;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class ApproveRequestForm extends Component
{
    use AuthorizesRequests;

    public Request $request;

    public $state = [
        'quantity_approved' => null,
        'reason' => null,
    ];

    public function approveRequest(UpdateRequestAction $updateRequestAction)
    {
        $this->authorize('approve', $this->request);

        if (! $this->request->broker->active) {
            throw ValidationException::withMessages([
                'authorization' => "Ce intermédiaire n'est pas autorisé à effectuer de nouvelles demandes.",
            ]);
        }

        if ($this->request->broker->hasPendingRequestOfType($this->request->attestation_type_id, [$this->request->id])) {
            throw ValidationException::withMessages([
                'authorization' => "Ce intermédiaire a déjà une demande du même type en cours de traitement.",
            ]);
        }

        if (! $this->request->broker->hasConsumedMinimumQuota($this->request->attestation_type_id)) {
            throw ValidationException::withMessages([
                'authorization' => "Ce intermédiaire n'a pas atteint son taux de consommation pour ce type.",
            ]);
        }

        $this->validate([
            'state.reason' => ['nullable', 'string', 'max:255'],
            'state.quantity_approved' => ['required', 'integer', 'min:1', 'max:' . $this->request->quantity],
        ]);

        $updateRequestAction->execute($this->request, $this->state);

        $this->request->state->transitionTo(Approved::class);

        session()->flash('success', 'La demande à été approuvée !');

        RequestApproved::dispatch($this->request);

        return redirect()->route('request.show', $this->request);
    }

    public function rejectRequest()
    {
        $this->authorize('reject', $this->request);

        $this->request->state->transitionTo(Rejected::class, $this->state['reason']);

        session()->flash('success', 'La demande à été rejétée !');

        RequestRejected::dispatch($this->request);

        return redirect()->route('request.show', $this->request);
    }

    public function render()
    {
        return view('livewire.request.approve-request-form', new RequestValidationFormViewModel($this->request));
    }
}

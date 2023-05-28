<?php

namespace App\Http\Livewire\Request;

use App\ViewModels\Request\RequestValidationFormViewModel;
use Domain\Request\Actions\UpdateRequestAction;
use Domain\Request\Actions\ValidateStockAction;
use Domain\Request\Events\RequestValidated;
use Domain\Request\Models\Request;
use Domain\Request\States\Validated;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class ValidateRequestForm extends Component
{
    use AuthorizesRequests;

    public Request $request;

    public $state = [
        'quantity_validated' => null,
    ];

    public function validateRequest(ValidateStockAction $validator, UpdateRequestAction $action)
    {
        $this->authorize('validate', $this->request);

        $this->validate([
            'state.quantity_validated' => ['required', 'integer', 'min:1', 'max:' . $this->request->quantity_approved],
        ]);

        $validator->execute($this->request, $this->state['quantity_validated'] ?? 0);

        $action->execute($this->request, $this->state);

        $this->request->state->transitionTo(Validated::class);

        session()->flash('success', 'La demande à été validée !');

        RequestValidated::dispatch($this->request);

        return redirect()->route('request.show', $this->request);
    }

    public function render()
    {
        $viewModel = new RequestValidationFormViewModel($this->request);

        return view('livewire.request.validate-request-form', $viewModel);
    }
}

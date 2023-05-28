<?php

namespace App\Http\Livewire\AttestationType;

use Domain\Attestation\Actions\CreateAttestationTypeAction;
use Domain\Attestation\Models\AttestationType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Component;

class CreateAttestationTypeForm extends Component
{
    use AuthorizesRequests;

    public $attestationType;

    public $state = [
        'name' => null,
        'description' => null,
        'color' => null,
        'is_requestable' => false,
    ];

    public function saveAttestationType(CreateAttestationTypeAction $createAttestationTypeAction)
    {
        $this->authorize('create', AttestationType::class);

        $this->validate([
            'state.name' => ['required', 'string', 'min:3', Rule::unique('attestation_types', 'name')],
            'state.color' => ['nullable', 'string', 'min:3', Rule::unique('attestation_types', 'color')],
            'state.description' => ['nullable', 'string', 'max:255'],
        ]);

        $createAttestationTypeAction->execute($this->state);

        session()->flash('success', "Le type d'attestation à été créé avec succès.");

        return redirect()->route('backend.imprimes.index');
    }

    public function render()
    {
        return view('livewire.attestation-type.create-attestation-type-form');
    }
}

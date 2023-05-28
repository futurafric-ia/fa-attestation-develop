<?php

namespace App\Http\Livewire\AttestationType;

use Domain\Attestation\Actions\UpdateAttestationTypeAction;
use Domain\Attestation\Models\AttestationType;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;

class EditAttestationTypeForm extends Component
{
    use AuthorizesRequests;

    public $attestationType;

    public $state = [
        'name' => null,
        'description' => null,
        'color' => null,
        'is_requestable' => false,
    ];

    public function mount(AttestationType $attestationType)
    {
        $this->attestationType = $attestationType;
        $this->state = $attestationType->withoutRelations()->toArray();
    }

    public function saveAttestationType(UpdateAttestationTypeAction $updateAttestationTypeAction)
    {
        $this->authorize('update', $this->attestationType);

        $this->validate([
            'state.name' => ['required', 'string', 'min:3'],
            'state.color' => ['nullable', 'string', 'min:3'],
            'state.description' => ['nullable', 'string', 'max:255'],
        ]);

        $updateAttestationTypeAction->execute($this->attestationType, $this->state);

        session()->flash('success', "Le type d'attestation à été mise à jour avec succès.");

        return redirect()->route('backend.imprimes.index');
    }

    public function render()
    {
        return view('livewire.attestation-type.edit-attestation-type-form');
    }
}

<?php

namespace App\Http\Livewire\AttestationType;

use Domain\Attestation\Actions\DeleteAttestationTypeAction;
use Domain\Attestation\Models\AttestationType;
use Livewire\Component;

class ListAttestationTypes extends Component
{
    public function render()
    {
        return view('livewire.attestation-type.list-attestation-types');
    }
}

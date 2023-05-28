<?php

namespace App\Http\Livewire\Attestation;

use Domain\Attestation\Models\Attestation;
use Livewire\Component;

class ListAnteriorAttestations extends Component
{
    public $displayingSlideover = false;

    public $attestationBeingDisplayed;

    protected $listeners = [
        'display-slideover' => 'displaySlideover',
    ];

    public function closeSlideover()
    {
        $this->displayingSlideover = false;
        $this->attestationBeingDisplayed = null;
    }

    public function displaySlideover($attestationId)
    {
        $this->displayingSlideover = true;
        $this->attestationBeingDisplayed = Attestation::withoutGlobalScope('currentAttestation')
            ->whereId($attestationId)
            ->with('deliveries.broker', 'scans.broker')
            ->first();
    }

    public function render()
    {
        return view('livewire.attestation.list-anterior-attestations');
    }
}

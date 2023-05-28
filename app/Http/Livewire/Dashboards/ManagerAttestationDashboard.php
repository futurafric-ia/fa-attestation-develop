<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Analytics\Analytics;
use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ManagerAttestationDashboard extends Component
{
    public $attestationType;

    public function clearAttestationTypeSelection()
    {
        $this->attestationType = null;
    }

    public function render()
    {
        $analytics = new Analytics();

        $totalAvailableStockByState = Attestation::select('state', DB::raw('count(*) as total'))
            ->groupBy('state')
            ->when($this->attestationType, function ($query) {
                return $query->where('attestation_type_id', $this->attestationType);
            })
            ->pluck('total', 'state');

        return view('livewire.dashboards.manager-attestation-dashboard', [
            'totalAvailableStock' => $analytics->totalAvailableStockForType($this->attestationType),
            'attestationTypes' => AttestationType::query()->pluck('name', 'id')->toArray(),
            'totalAvailableStockByState' => $totalAvailableStockByState
        ]);
    }
}

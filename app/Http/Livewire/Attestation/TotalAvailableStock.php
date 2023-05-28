<?php

namespace App\Http\Livewire\Attestation;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TotalAvailableStock extends Component
{
    public function render()
    {
        $totalAvailableStockByType = Attestation::deliverable()
            ->select(['attestation_type_id', DB::raw('count(`id`) as total')])
            ->groupBy('attestation_type_id')
            ->pluck('total', 'attestation_type_id');

        collect([AttestationType::YELLOW, AttestationType::BROWN, AttestationType::GREEN])->each(function ($attestationType) use ($totalAvailableStockByType) {
            if (!isset($totalAvailableStockByType[$attestationType])) {
                $totalAvailableStockByType[$attestationType] = 0;
            }
        });

        return view('livewire.attestation.total-available-stock', [
            'attestationTypes' => AttestationType::pluck('name', 'id')->toArray(),
            'totalAvailableStockByType' => $totalAvailableStockByType,
        ]);
    }
}

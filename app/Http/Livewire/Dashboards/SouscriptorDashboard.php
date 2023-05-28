<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Domain\Authorization\Models\Role;
use Domain\Scan\Models\ScanMismatch;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class SouscriptorDashboard extends Component
{
    public function mount()
    {
        abort_if(! auth()->user()->hasRole(Role::SOUSCRIPTOR), 403);
    }

    public function render()
    {
        $totalScanMismatches = ScanMismatch::select(['attestation_type_id', DB::raw('count(*) as total')])
            ->groupBy('attestation_type_id')
            ->pluck('total', 'attestation_type_id');

        $totalAttestationAnterior = Attestation::withoutGlobalScope('currentAttestation')
            ->select(['attestation_type_id', DB::raw('count(*) as total')])
            ->where('anterior', true)
            ->groupBy('attestation_type_id')
            ->pluck('total', 'attestation_type_id');

        $totalSucceddedScan = Attestation::select(['attestation_type_id', DB::raw('count(*) as total')])
            ->whereNotNull('last_scan_id')
            ->groupBy('attestation_type_id')
            ->pluck('total', 'attestation_type_id');

        collect([AttestationType::YELLOW, AttestationType::BROWN, AttestationType::GREEN])->each(function ($attestationType) use ($totalScanMismatches, $totalAttestationAnterior, $totalSucceddedScan) {
            if (!isset($totalScanMismatches[$attestationType])) {
                $totalScanMismatches[$attestationType] = 0;
            }

            if (!isset($totalAttestationAnterior[$attestationType])) {
                $totalAttestationAnterior[$attestationType] = 0;
            }

            if (!isset($totalSucceddedScan[$attestationType])) {
                $totalSucceddedScan[$attestationType] = 0;
            }
        });

        return view('livewire.dashboards.souscriptor-dashboard', [
            'totalScanMismatches' => ScanMismatch::count(),
            'totalYellowScanMismatches' => $totalScanMismatches[AttestationType::YELLOW],
            'totalGreenScanMismatches' => $totalScanMismatches[AttestationType::GREEN],
            'totalBrownScanMismatches' => $totalScanMismatches[AttestationType::BROWN],
            'totalAttestationAnterior' => Attestation::withoutGlobalScope('currentAttestation')->where('anterior', true)->count(),
            'totalYellowAttestationAnterior' => $totalAttestationAnterior[AttestationType::YELLOW],
            'totalGreenAttestationAnterior' => $totalAttestationAnterior[AttestationType::GREEN],
            'totalBrownAttestationAnterior' => $totalAttestationAnterior[AttestationType::BROWN],
            'totalSucceddedScan' => Attestation::whereNotNull('last_scan_id')->count(),
            'totalYellowSucceddedScan' => $totalSucceddedScan[AttestationType::YELLOW],
            'totalGreenSucceddedScan' => $totalSucceddedScan[AttestationType::GREEN],
            'totalBrownSucceddedScan' => $totalSucceddedScan[AttestationType::BROWN],
        ]);
    }
}

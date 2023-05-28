<?php

namespace App\ViewModels\Scan;

use Domain\Attestation\Models\AttestationType;
use Domain\Attestation\States\Cancelled;
use Domain\Attestation\States\Returned;
use Domain\Attestation\States\Used;
use Domain\Broker\Models\Broker;
use Domain\Scan\Models\Scan;
use Domain\User\Models\User;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class ScanFormViewModel extends ViewModel
{
    private ?string $scanType;
    private ?User $user;

    public function __construct(?string $scanType = null, ?User $user = null)
    {
        $this->scanType = $scanType;
        $this->user = $user;
    }

    public function attestationTypes(): Collection
    {
        return AttestationType::query()->pluck('name', 'id');
    }

    public function brokers(): Collection
    {
        return Broker::query()->limit(25)->orderBy('name')->pluck('name', 'id');
    }

    public function canChooseAttestationState()
    {
        return $this->scanType === Scan::TYPE_MANUAL;
    }

    public function attestationStates()
    {
        if ($this->user->can('delivery.create')) {
            return collect([
                Returned::$name => 'Retournée',
                Cancelled::$name => 'Annulée',
            ]);
        }

        return collect([Used::$name => 'Utilisée']);
    }
}

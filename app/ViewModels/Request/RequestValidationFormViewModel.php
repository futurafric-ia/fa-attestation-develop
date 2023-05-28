<?php

namespace App\ViewModels\Request;

use Domain\Attestation\Models\Attestation;
use Domain\Attestation\Models\AttestationType;
use Domain\Attestation\States\Attributed;
use Domain\Attestation\States\Available;
use Domain\Attestation\States\Returned;
use Domain\Request\Models\Request;
use Spatie\ViewModels\ViewModel;

class RequestValidationFormViewModel extends ViewModel
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request->load('broker', 'attestationType');
    }

    public function broker(): \Domain\Broker\Models\Broker
    {
        return $this->request->broker;
    }

    public function request(): Request
    {
        return $this->request;
    }

    public function isAuthorized(): int
    {
        return $this->request->broker->active;
    }

    public function hasConsumedMinimumQuota(): bool
    {
        return $this->request->broker->hasConsumedMinimumQuota($this->request->attestation_type_id);
    }

    public function canValidate(): bool
    {
        return $this->isAuthorized() && $this->hasConsumedMinimumQuota();
    }

    public function hasPendingRequest(): bool
    {
        return 0 !== $this->request->broker->hasPendingRequestOfType($this->request->attestation_type_id);
    }

    public function lastDelivery()
    {
        return $this->request->broker
            ->deliveries()
            ->where('attestation_type_id', $this->request->attestation_type_id)
            ->limit(1)
            ->latest()
            ->first();
    }

    public function availableStock(): int
    {
        return Attestation::query()
            ->where('attestation_type_id', $this->request->attestation_type_id)
            ->whereState('state', [Available::class, Returned::class])
            ->count();
    }

    public function brokerStock()
    {
        return $this->request->broker
            ->attestations()
            ->whereState('state', Attributed::class)
            ->where('attestation_type_id', $this->request->attestation_type_id)
            ->count();
    }

    public function showBrownAttestationStock(): bool
    {
        return AttestationType::YELLOW === $this->request->attestation_type_id;
    }

    public function availableBrownAttestationStock(): int
    {
        return Attestation::query()
            ->where('attestation_type_id', AttestationType::BROWN)
            ->whereState('state', [Available::class, Returned::class])
            ->count();
    }
}

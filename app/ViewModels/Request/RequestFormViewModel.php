<?php

namespace App\ViewModels\Request;

use Domain\Attestation\Models\AttestationType;
use Domain\Request\Models\Request;
use Illuminate\Support\Collection;
use Spatie\ViewModels\ViewModel;

class RequestFormViewModel extends ViewModel
{
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    public function request()
    {
        return $this->request;
    }

    public function attestationTypes(): Collection
    {
        return AttestationType::where('is_requestable', true)->pluck('name', 'id');
    }

    public function broker()
    {
        return auth()->user()->broker;
    }
}

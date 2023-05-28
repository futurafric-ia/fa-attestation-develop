<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttestationResource;

class BrokerAttestationController extends Controller
{
    public function index()
    {
        return AttestationResource::collection(broker()->attestations()->paginate(25))->response();
    }

    public function show($attestation)
    {
        return (new AttestationResource(broker()->attestations()->where('attestation_number', $attestation)->firstOrFail()))->response();
    }

    public function showStatus($attestation)
    {
        $item = broker()->attestations()->where('attestation_number', $attestation)->firstOrFail();

        return response()->json(['data' => $item->state->label()]);
    }
}

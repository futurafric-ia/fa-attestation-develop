<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RequestResource;
use Domain\Attestation\Models\AttestationType;
use Domain\Request\Actions\CreateRequestAction;
use Domain\Request\Actions\UpdateRequestAction;
use Domain\Request\Events\RequestCancelled;
use Domain\Request\Events\RequestCreated;
use Domain\Request\Events\RequestUpdated;
use Domain\Request\Models\Request;
use Domain\Request\States\Cancelled;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class BrokerRequestController extends Controller
{
    public function index()
    {
        return RequestResource::collection(broker()->requests()->paginate(25))->response();
    }

    public function show($request)
    {
        $request = broker()->requests()->where('id', $request)->firstOrFail();

        return (new RequestResource($request))->response();
    }

    public function store(Request $request, CreateRequestAction $createRequestAction)
    {
        $validated = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'attestation_type_id' => ['required', Rule::in(AttestationType::where('is_requestable', true)->pluck('id'))],
            'expected_at' => ['nullable', 'date', 'after:' . date('Y-m-d')],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $request = $createRequestAction->execute(array_merge($validated, [
            'broker_id' => broker()->id,
            'created_by' => auth()->id(),
        ]));

        RequestCreated::dispatch($request);

        return (new RequestResource($request))->response();
    }

    public function update(\Illuminate\Http\Request $httpRequest, Request $request, UpdateRequestAction $updateRequestAction)
    {
        $validated = $httpRequest->validate([
            'quantity' => ['required', 'integer', 'min:1'],
            'attestation_type_id' => ['required', Rule::in(AttestationType::where('is_requestable', true)->pluck('id'))],
            'expected_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:255'],
        ]);

        $request = broker()->requests()->where('id', $request)->firstOrFail();

        RequestUpdated::dispatch($updateRequestAction->execute($request, $validated));

        return (new RequestResource($request->fresh()))->response();
    }

    public function destroy(\Illuminate\Http\Request $httpRequest, Request $request)
    {
        $httpRequest->validate(['reason' => ['nullable', 'max:255']]);

        $request = broker()->requests()->where('id', $request)->firstOrFail();

        $request->state->transitionTo(Cancelled::class, $request->get('reason'));

        RequestCancelled::dispatch($request);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}

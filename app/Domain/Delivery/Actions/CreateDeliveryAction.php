<?php

namespace Domain\Delivery\Actions;

use DB;
use Domain\Delivery\Events\AttestationsAssigned;
use Domain\Delivery\Events\DeliveryCreated;
use Domain\Delivery\Jobs\AssignAttestationsToBroker;
use Domain\Delivery\Models\Delivery;
use Domain\Request\Events\RequestDelivered;
use Domain\Request\Models\Request;
use Illuminate\Bus\Batch;
use Illuminate\Support\Facades\Bus;

final class CreateDeliveryAction
{
    private ValidateDeliveryRangeAction $validator;

    public function __construct(ValidateDeliveryRangeAction $validator)
    {
        $this->validator = $validator;
    }

    public function execute(Request $request, array $data): Delivery
    {
        $this->validator->execute($request, $data['main']['ranges']);

        if ($request->isRelated()) {
            $this->validator->execute($request->related(), $data['related']['ranges']);
        }

        DB::beginTransaction();

        $delivery = Delivery::create([
            'quantity' => $request->quantity_validated,
            'request_id' => $request->id,
            'broker_id' => $request->broker_id,
            'attestation_type_id' => $request->attestation_type_id,
            'delivered_by' => $data['delivered_by'],
        ]);

        $relatedDelivery = null;

        if ($request->isRelated()) {
            $relatedDelivery = Delivery::create([
                'quantity' => $request->related()->quantity_validated,
                'request_id' => $request->related()->id,
                'broker_id' => $request->related()->broker_id,
                'attestation_type_id' => $request->related()->attestation_type_id,
                'delivered_by' => $data['delivered_by'],
            ]);
        }

        DB::commit();

        DeliveryCreated::dispatch($delivery);

        $delivery->load('request');

        $jobs = [new AssignAttestationsToBroker($delivery, $data['main']['ranges'])];

        if ($relatedDelivery && $request->isRelated()) {
            $relatedDelivery->load('request');

            $jobs[] = new AssignAttestationsToBroker($relatedDelivery, $data['related']['ranges']);
        }

        $batch = Bus::batch($jobs)->name('AssignAttestationsToBroker')
            ->then(function (Batch $batch) use ($delivery) {
                AttestationsAssigned::dispatch($delivery);
                RequestDelivered::dispatch($delivery->request, $delivery);
            })->dispatch();

        $delivery->update(['batch_id' => $batch->id]);

        return $delivery;
    }
}

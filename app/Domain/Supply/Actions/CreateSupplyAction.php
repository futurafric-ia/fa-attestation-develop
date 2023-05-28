<?php

namespace Domain\Supply\Actions;

use Domain\Supply\Events\SupplyCreated;
use Domain\Supply\Jobs\GenerateAttestations;
use Domain\Supply\Models\Supply;
use Domain\Supply\States\Pending;

final class CreateSupplyAction
{
    protected ValidateSupplyRangeAction $validator;

    public function __construct(ValidateSupplyRangeAction $validator)
    {
        $this->validator = $validator;
    }

    public function execute(array $data): Supply
    {
        $this->validator->execute([['range_start' => $data['range_start'],'range_end' => $data['range_end'],]]);

        $supply = Supply::create([
            'attestation_type_id' => $data['attestation_type_id'],
            'received_at' => $data['received_at'],
            'range_start' => $data['range_start'],
            'range_end' => $data['range_end'],
            'quantity' => ($data['range_end'] - $data['range_start']) + 1,
            'supplier_id' => $data['supplier_id'],
            'state' => Pending::$name,
        ]);

        SupplyCreated::dispatch($supply);

        GenerateAttestations::dispatch($supply);

        return $supply;
    }
}

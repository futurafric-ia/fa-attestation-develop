<?php

namespace Domain\Supply\Actions;

use Domain\Supply\Events\SupplierCreated;
use Domain\Supply\Models\Supplier;

final class CreateSupplierAction
{
    public function execute(array $data): Supplier
    {
        $supplier = Supplier::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'email' => $data['email'] ?? null,
            'type' => $data['type'] ?? null,
            'contact' => $data['contact'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        SupplierCreated::dispatch($supplier);

        return $supplier;
    }
}

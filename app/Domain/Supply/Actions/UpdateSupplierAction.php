<?php

namespace Domain\Supply\Actions;

use Domain\Supply\Events\SupplierUpdated;
use Domain\Supply\Models\Supplier;

final class UpdateSupplierAction
{
    public function execute(Supplier $supplier, array $data): Supplier
    {
        $supplier->update([
            'code' => $data['code'] ?? $supplier->name,
            'name' => $data['name'] ?? $supplier->name,
            'type' => $data['type'] ?? $supplier->type,
            'email' => $data['email'] ?? $supplier->email,
            'contact' => $data['contact'] ?? $supplier->contact,
            'address' => $data['address'] ?? $supplier->address,
        ]);

        $supplier->refresh();

        SupplierUpdated::dispatch($supplier);

        return $supplier;
    }
}

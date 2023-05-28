<?php

namespace Domain\Broker\Actions;

use Domain\Broker\Events\BrokerUpdated;
use Domain\Broker\Models\Broker;

final class UpdateBrokerAction
{
    public function execute(Broker $broker, array $data): Broker
    {
        $broker->update([
            'name' => $data['name'] ?? $broker->name,
            'code' => $data['code'] ?? $broker->code,
            'email' => $data['email'] ?? $broker->email,
            'address' => $data['address'] ?? $broker->address,
            'contact' => $data['contact'] ?? $broker->contact,
            'notes' => $data['notes'] ?? $broker->notes,
            'active' => $data['active'] ?? $broker->active,
            'department_id' => $data['department_id'] ?? $broker->department_id,
            'minimum_consumption_percentage' => $data['minimum_consumption_percentage'] ?? $broker->minimum_consumption_percentage,
        ]);

        if (isset($data['logo'])) {
            $broker->updateProfilePhoto($data['logo']);
        }

        BrokerUpdated::dispatch($broker);

        return $broker->fresh();
    }
}

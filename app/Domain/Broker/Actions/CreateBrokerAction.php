<?php

namespace Domain\Broker\Actions;

use Domain\Broker\Events\BrokerCreated;
use Domain\Broker\Models\Broker;
use Domain\User\Actions\CreateUserAction;
use Domain\User\Models\User;
use Illuminate\Support\Facades\DB;

final class CreateBrokerAction
{
    protected CreateUserAction $createUserAction;

    public function __construct(CreateUserAction $createUserAction)
    {
        $this->createUserAction = $createUserAction;
    }

    public function execute(array $data): Broker
    {
        DB::beginTransaction();

        $broker = Broker::create([
            'name' => $data['name'],
            'code' => $data['code'],
            'email' => $data['email'],
            'department_id' => $data['department_id'],
            'address' => $data['address'] ?? null,
            'contact' => $data['contact'] ?? null,
            'notes' => $data['notes'] ?? null,
            'logo_url' => $data['logo_url'] ?? null,
            'active' => $data['active'] ?? true,
            'minimum_consumption_percentage' => config('saham.brokers.minimum_consumption_percentage'),
        ]);


        if (isset($data['owner'])) {
            // Rétirer les valeurs `fausses` pour éviter d'écraser les informations partagées
            $ownerInfos = collect($data['owner'])->filter()->toArray();
            $user = $this->createUser(array_merge($data, $ownerInfos));
            $broker->addUser($user);
            $broker->setOwner($user);
        }

        DB::commit();

        BrokerCreated::dispatch($broker);

        return $broker;
    }

    private function createUser($data): User
    {
        return $this->createUserAction->execute([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'contact' => $data['contact'],
            'address' => $data['address'],
            'department_id' => $data['department_id'],
            'type' => User::TYPE_BROKER,
        ]);
    }
}

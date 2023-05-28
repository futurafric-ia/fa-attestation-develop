<?php

namespace Database\Seeders;

use Domain\Broker\Actions\CreateBrokerAction;
use Domain\Broker\Models\Broker;
use Domain\Department\Models\Department;
use Illuminate\Database\Seeder;

class DummyBrokersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (Department::all() as $department) {
            $broker = Broker::factory()->make(['department_id' => $department->id])->toArray();

            app(CreateBrokerAction::class)->execute(array_merge(
                $broker,
                [
                    'owner' => ['first_name' => $broker['name'], 'last_name' => $broker['name']],
                ]
            ));
        }
    }
}

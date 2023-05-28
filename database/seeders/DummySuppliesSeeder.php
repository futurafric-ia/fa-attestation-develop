<?php

namespace Database\Seeders;

use Domain\Attestation\Models\AttestationType;
use Domain\Supply\Actions\CreateSupplyAction;
use Domain\Supply\Models\Supply;
use Illuminate\Database\Seeder;

class DummySuppliesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $attestationTypesIds = AttestationType::pluck('id');

        foreach ($attestationTypesIds as $attestationTypeId) {
            dd(Supply::factory()->state([
                'supplier_id' => 1,
                'attestation_type_id' => $attestationTypeId,
            ])->make()->toArray());
            app(CreateSupplyAction::class)->execute();
        }
    }
}

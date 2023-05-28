<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Domain\Attestation\Models\AttestationType;
use Illuminate\Database\Seeder;

class AttestationTypesSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        AttestationType::query()->create([
            'id' => AttestationType::YELLOW,
            'name' => 'Jaune',
            'color' => '#FFFF00',
            'description' => 'Attestations automobiles',
        ]);

        AttestationType::query()->create([
            'id' => AttestationType::GREEN,
            'name' => 'Verte',
            'color' => '#32CD32',
            'description' => 'Attestations deux roues',
        ]);

        AttestationType::query()->create([
            'id' => AttestationType::BROWN,
            'name' => 'Brune',
            'color' => '#DCDCDC',
            'description' => 'Carte(s) brune CEDEAO',
            'is_requestable' => false,
        ]);

        $this->enableForeignKeys();
    }
}

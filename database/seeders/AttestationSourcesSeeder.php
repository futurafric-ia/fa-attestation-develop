<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Domain\Attestation\Models\AttestationSource;
use Illuminate\Database\Seeder;

class AttestationSourcesSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->disableForeignKeys();

        AttestationSource::create(['name' => 'system',]);
        AttestationSource::create(['name' => 'milliard',]);

        $this->enableForeignKeys();
    }
}

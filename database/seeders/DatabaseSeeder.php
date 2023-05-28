<?php

namespace Database\Seeders;

use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use TruncateTable;

    /**
     * Seed the application's database.
     */
    public function run()
    {
        Model::unguard();

        $this->truncateMultiple(['failed_jobs',]);

        $this->call(AuthSeeder::class);
        $this->call(DepartmentsSeeder::class);
        $this->call(AttestationTypesSeeder::class);
        $this->call(AttestationSourcesSeeder::class);

        Model::reguard();
    }
}

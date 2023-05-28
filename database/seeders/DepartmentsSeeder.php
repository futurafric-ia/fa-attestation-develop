<?php

namespace Database\Seeders;

use Domain\Department\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Department::create(['name' => 'Agence générale']);
        Department::create(['name' => 'Courtage']);
        Department::create(['name' => 'Bankassurance']);
        Department::create(['name' => 'Mass Market']);
        Department::create(['name' => 'Bureau Direct']);
    }
}

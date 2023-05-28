<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DummyUsersSeeder::class);
        $this->call(DummySuppliersSeeder::class);
        $this->call(DummySuppliesSeeder::class);
        $this->call(DummyBrokersSeeder::class);
    }
}

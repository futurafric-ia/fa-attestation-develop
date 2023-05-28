<?php

namespace Database\Seeders;

use Domain\Supply\Actions\CreateSupplierAction;
use Domain\Supply\Models\Supplier;
use Illuminate\Database\Seeder;

class DummySuppliersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app(CreateSupplierAction::class)->execute(Supplier::factory()->make()->toArray());
    }
}

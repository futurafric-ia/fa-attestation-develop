<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        User::create([
            'identifier' => 'SAD001',
            'last_name' => 'Super',
            'first_name' => 'Admin',
            'email' => 'super-admin@saham.com',
            'password' => 'password',
            'email_verified_at' => now(),
            'active' => true,
            'address' => '00 BP 0000 Abidjan 00',
            'type' => User::TYPE_ADMIN,
            'contact' => '08986754',
            'api_token' => Str::random(60),
        ]);

        User::create([
            'identifier' => 'AD001',
            'last_name' => 'Admin',
            'first_name' => 'Saham',
            'email' => 'admin@saham.com',
            'password' => 'password',
            'email_verified_at' => now(),
            'type' => User::TYPE_USER,
            'active' => true,
            'address' => '00 BP 0000 Abidjan 00',
            'contact' => '08986754',
            'api_token' => Str::random(60),
        ]);

        $this->enableForeignKeys();
    }
}

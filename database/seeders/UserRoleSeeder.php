<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Domain\Authorization\Models\Role;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    use DisableForeignKeys;

    /**
     * Run the database seed.
     */
    public function run()
    {
        $this->disableForeignKeys();

        User::find(1)->assignRole(Role::SUPER_ADMIN);

        User::find(2)->assignRole(Role::ADMIN);

        $this->enableForeignKeys();
    }
}

<?php

namespace Database\Seeders;

use Database\Seeders\Traits\DisableForeignKeys;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

class AuthSeeder extends Seeder
{
    use DisableForeignKeys;
    use TruncateTable;

    /**
     * Run the database seeders.
     */
    public function run()
    {
        $this->disableForeignKeys();

        // Reset cached roles and permissions
        Artisan::call('cache:clear');

        resolve(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->truncateMultiple([
            config('permission.table_names.model_has_permissions'),
            config('permission.table_names.model_has_roles'),
            config('permission.table_names.role_has_permissions'),
            config('permission.table_names.permissions'),
            config('permission.table_names.roles'),
            'users',
            // 'password_histories',
            'password_resets',
        ]);

        $this->call(UserSeeder::class);
        $this->call(PermissionsAndRolesSeeder::class);
        $this->call(UserRoleSeeder::class);

        $this->enableForeignKeys();
    }
}

<?php

namespace Database\Seeders;

use Domain\Authorization\Models\Role;
use Domain\Department\Models\Department;
use Domain\User\Models\User;
use Illuminate\Database\Seeder;

class DummyUsersSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        Department::all()->each(function (Department $department) {
            $validator = User::factory()->create(['email' => "validateur-{$department->slug}@saham.com"]);
            $validator->assignRole(Role::VALIDATOR);
            $validator->departments()->attach($department->id);
        });

        $supervisor = User::factory()->create(['email' => 'chef-stock@saham.com']);
        $supervisor->assignRole(Role::SUPERVISOR);

        $manager = User::factory()->create(['email' => 'gestionnaire-stock@saham.com']);
        $manager->assignRole(Role::MANAGER);

        $souscriptor = User::factory()->create(['email' => 'souscripteur@saham.com']);
        $souscriptor->assignRole(Role::SOUSCRIPTOR);

        $auditor = User::factory()->create(['email' => 'controlleur-interne@saham.com']);
        $auditor->assignRole(Role::AUDITOR);
    }
}

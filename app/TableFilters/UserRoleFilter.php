<?php

namespace App\TableFilters;

use Domain\Authorization\Models\Role;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class UserRoleFilter
{
    public static $title = "Rôles";
    public static $id = 'role';

    public static function make()
    {
        $roles = Role::allowedForUser(auth()->user())->pluck('name', 'id')->toArray();

        return Filter::make(self::$title)->select(['' => 'Tous'] + $roles);
    }
}

<?php

namespace Domain\Request\States;

use Domain\Authorization\Models\Role;

class Validated extends RequestState
{
    public static $name = 'validated';

    public function label(): string
    {
        return auth()->user()->hasRole(Role::MANAGER) ? 'En attente' : 'Validée';
    }

    public function color(): string
    {
        return auth()->user()->hasRole(Role::MANAGER) ? 'bg-gray-400' : 'bg-green-500';
    }

    public function textColor(): string
    {
        return auth()->user()->hasRole(Role::MANAGER) ? 'text-gray-50' : 'text-gray-50';
    }
}

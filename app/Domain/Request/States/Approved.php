<?php

namespace Domain\Request\States;

use Domain\Authorization\Models\Role;

class Approved extends RequestState
{
    public static $name = 'approved';

    public function label(): string
    {
        return auth()->user()->hasRole(Role::SUPERVISOR) ? 'En attente' : 'Approuvée';
    }

    public function color(): string
    {
        return auth()->user()->hasRole(Role::SUPERVISOR) ? 'bg-gray-400' : 'bg-orange-500';
    }

    public function textColor(): string
    {
        return auth()->user()->hasRole(Role::SUPERVISOR) ? 'text-gray-50' : 'text-gray-50';
    }
}

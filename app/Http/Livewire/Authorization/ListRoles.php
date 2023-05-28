<?php

namespace App\Http\Livewire\Authorization;

use Domain\Authorization\Actions\DestroyRoleAction;
use Domain\Authorization\Models\Role;
use Livewire\Component;

class ListRoles extends Component
{
    public function render()
    {
        return view('livewire.authorization.list-roles');
    }
}

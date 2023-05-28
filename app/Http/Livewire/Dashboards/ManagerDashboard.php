<?php

namespace App\Http\Livewire\Dashboards;

use Domain\Authorization\Models\Role;
use Livewire\Component;

class ManagerDashboard extends Component
{
    public function mount()
    {
        abort_if(! auth()->user()->hasRole(Role::MANAGER), 403);
    }

    public function render()
    {
        return view('livewire.dashboards.manager-dashboard');
    }
}

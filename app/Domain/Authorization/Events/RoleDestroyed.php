<?php

namespace Domain\Authorization\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Spatie\Permission\Models\Role;

class RoleDestroyed
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public Role $role;

    /**
     * @param Request $request
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }
}

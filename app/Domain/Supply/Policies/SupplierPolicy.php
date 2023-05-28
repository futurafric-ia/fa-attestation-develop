<?php

namespace Domain\Supply\Policies;

use Domain\Supply\Models\Supplier;
use Domain\User\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SupplierPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        if ($user->can('supplier.list')) {
            return true;
        }
    }

    public function view(User $user, Supplier $supplier)
    {
        if ($user->can('supplier.list')) {
            return true;
        }
    }

    public function create(User $user)
    {
        if ($user->can('supplier.create')) {
            return true;
        }
    }

    public function update(User $user, Supplier $supplier)
    {
        if ($user->can('supplier.update')) {
            return true;
        }
    }

    public function delete(User $user, Supplier $supplier)
    {
        if ($user->can('supplier.delete')) {
            return true;
        }
    }
}

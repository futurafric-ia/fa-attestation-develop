<?php

namespace Domain\Authorization\Models;

use Domain\User\Models\User;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    // IMPORTANT: Should not be changed
    // Base User roles ID, does not rely on the name because it can be changed
    public const SUPER_ADMIN = 1;
    public const ADMIN = 2;
    public const BROKER = 3;
    public const VALIDATOR = 4;
    public const SUPERVISOR = 5;
    public const MANAGER = 6;
    public const SOUSCRIPTOR = 7;
    public const AUDITOR = 8;

    public function scopeAllowedForUser($builder, ?User $user = null)
    {
        if (! $user) {
            return $builder;
        }

        if ($user->isSuperAdmin()) {
            return $builder->whereNotIn('id', [Role::BROKER]);
        }

        return $builder->whereNotIn('id', [Role::SUPER_ADMIN, Role::BROKER]);
    }

    public function scopeRolesWithDeparment($builder)
    {
        return $builder->where('has_department', true);
    }

    public function getPermissionsLabelAttribute(): string
    {
        if ($this->isAdmin()) {
            return __('Tous');
        }

        if (! $this->permissions->count()) {
            return __('Aucun');
        }

        return collect($this->getPermissionDescriptions());
    }

    public function hasDepartment()
    {
        return static::rolesWithDeparment()->pluck('id')->contains($this->id);
    }

    public function isSuperAdmin(): bool
    {
        return $this->id === config('saham.access.role.super_admin');
    }

    public function getPermissionDescriptions(): Collection
    {
        return $this->permissions->pluck('description');
    }
}

<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        \Domain\User\Models\User::class => \Domain\User\Policies\UserPolicy::class,
        \Domain\Broker\Models\Broker::class => \Domain\Broker\Policies\BrokerPolicy::class,
        \Domain\Supply\Models\Supplier::class => \Domain\Supply\Policies\SupplierPolicy::class,
        \Domain\Supply\Models\Supply::class => \Domain\Supply\Policies\SupplyPolicy::class,
        \Domain\Request\Models\Request::class => \Domain\Request\Policies\RequestPolicy::class,
        \Domain\Department\Models\Department::class => \Domain\Department\Policies\DepartmentPolicy::class,
        \Domain\Delivery\Models\Delivery::class => \Domain\Delivery\Policies\DeliveryPolicy::class,
        \Domain\Attestation\Models\Attestation::class => \Domain\Attestation\Policies\AttestationPolicy::class,
        \Domain\Attestation\Models\AttestationType::class => \Domain\Attestation\Policies\AttestationTypePolicy::class,
        \Domain\Logger\Models\Activity::class => \Domain\Logger\Policies\ActivityPolicy::class,
        \Domain\Authorization\Models\Role::class => \Domain\Authorization\Policies\RolePolicy::class,
        \Illuminate\Notifications\DatabaseNotification::class => \Domain\Logger\Policies\NotificationPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Implicitly grant "Super Admin" role all permissions
        // This works in the app by using gate-related functions like auth()->user->can() and @can()
        Gate::before(function ($user) {
            return $user->isSuperAdmin() ? true : null;
        });
    }
}

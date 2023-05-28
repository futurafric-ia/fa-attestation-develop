<?php

namespace App\Providers;

use Domain\Authorization\Listeners\RoleEventListener;
use Domain\Broker\Listeners\BrokerEventListener;
use Domain\Delivery\Listeners\DeliveryEventListener;
use Domain\Request\Listeners\RequestEventListener;
use Domain\Scan\Events\ScanCompleted;
use Domain\Scan\Events\ScanFailed;
use Domain\Scan\Listeners\ReleaseAzureResources;
use Domain\Scan\Listeners\ScanEventListener;
use Domain\Supply\Listeners\SupplierEventListener;
use Domain\Supply\Listeners\SupplyEventListener;
use Domain\User\Listeners\UserEventListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ScanFailed::class => [
            ReleaseAzureResources::class,
        ],
        ScanCompleted::class => [
            ReleaseAzureResources::class,
        ],
    ];

    /**
     * Class event subscribers.
     *
     * @var array
     */
    protected $subscribe = [
        UserEventListener::class,
        ScanEventListener::class,
        SupplyEventListener::class,
        DeliveryEventListener::class,
        RequestEventListener::class,
        BrokerEventListener::class,
        RoleEventListener::class,
        SupplierEventListener::class,
        RoleEventListener::class,
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}

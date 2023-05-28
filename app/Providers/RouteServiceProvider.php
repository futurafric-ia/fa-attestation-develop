<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = '';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::model('user', \Domain\User\Models\User::class);
        Route::model('request', \Domain\Request\Models\Request::class);
        Route::model('delivery', \Domain\Delivery\Models\Delivery::class);
        Route::model('department', \Domain\Department\Models\Department::class);
        Route::model('supplier', \Domain\Supply\Models\Supplier::class);
        Route::model('supply', \Domain\Supply\Models\Supply::class);
        Route::model('broker', \Domain\Broker\Models\Broker::class);
        Route::model('attestation', \Domain\Attestation\Models\Attestation::class);
        Route::model('attestationType', \Domain\Attestation\Models\AttestationType::class);
        Route::model('scan', \Domain\Scan\Models\Scan::class);
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }
}

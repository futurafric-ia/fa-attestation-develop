<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Support\AzureMockService;
use Support\AzureService;
use Support\AzureServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('saham.testing')) {
            $this->app->bind(
                AzureServiceInterface::class,
                AzureMockService::class
            );
        } else {
            $this->app->bind(
                AzureServiceInterface::class,
                AzureService::class
            );
        }

        Factory::guessFactoryNamesUsing(function (string $modelName) {
            return 'Database\Factories\\' . class_basename($modelName) . 'Factory';
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
    }
}

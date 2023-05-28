<?php

namespace App\Providers;

use ConsoleTVs\Charts\Registrar as Charts;
use Illuminate\Support\ServiceProvider;

class ChartServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @param Charts $charts
     * @return void
     */
    public function boot(Charts $charts)
    {
        $charts->register([
            \App\Charts\DeliveryByTypeAndMonthChart::class,
            \App\Charts\RequestApprovedByTypeAndMonthChart::class,
            \App\Charts\RequestValidatedByTypeAndMonthChart::class,
            \App\Charts\AttestationByStateChart::class,
            \App\Charts\DeliveryRequestRatioChart::class,
        ]);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],
        'App\Events\MeterDataReceived' => [
            'App\Listeners\CalculateEnergyBudget',
            'App\Listeners\UpdateDashboard'
        ],
        'App\Events\TamperDetected' => [
            'App\Listeners\NotifyUser',
            'App\Listeners\NotifyDisco'
        ],
        'App\Events\PaymentCompleted' => [
            'App\Listeners\RechargeMeter',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

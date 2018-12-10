<?php

namespace App\Listeners;

use App\Events\MeterDataReceived;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class CalculateEnergyBudget
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MeterDataReceived  $event
     * @return void
     */
    public function handle(MeterDataReceived $event)
    {
        //
    }
}

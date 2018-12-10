<?php

namespace App\Listeners;

use App\Events\TamperDetected;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUser
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
     * @param  TamperDetected  $event
     * @return void
     */
    public function handle(TamperDetected $event)
    {
        //
    }
}

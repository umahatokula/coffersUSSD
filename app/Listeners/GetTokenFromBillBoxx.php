<?php

namespace App\Listeners;

use App\Events\PowerWasPurchased;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GetTokenFromBillBoxx implements ShouldQueue
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
     * @param  \App\Events\PowerWasPurchased  $event
     * @return void
     */
    public function handle(PowerWasPurchased $event)
    {
        print_r($event->transaction->amount);
        \Log::info($event->transaction->amount);
    }
}

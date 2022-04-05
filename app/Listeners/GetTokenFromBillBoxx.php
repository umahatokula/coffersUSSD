<?php

namespace App\Listeners;

use App\Events\PowerWasPurchased;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use GuzzleHttp\Client;

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
        // \Log::info($event->transaction->amount);

        try {

            $client = new Client();
            $response = $client->post('http://198.71.50.252/gateway/', [
                'disco' => 'Pay Electricity aedc',
                'meter_number' => '30530035598',
                'payment_type' => 'prepaid',
                'msisdn' => '08034910941',
                'amount' => '200',
                'action' => 'electricity_verify',
            ]);

            if ($response->getStatus() == 200) {

                echo $response->getBody();

            } else {

                echo 'Unexpected HTTP status: ' . $response->getStatus() . ' ' .
                $response->getReasonPhrase();

            }
        }
        catch(HTTP_Request2_Exception $e) {

            echo 'Error: ' . $e->getMessage();

        }

    }
}

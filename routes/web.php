<?php

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('/callback', [CustomerController::class, 'index'])->name('callback');

Route::get('test', function() {

    try {

        $client = new Client();
        $response = $client->post('http://198.71.50.252/gateway/',
            [
                'headers' => [
                    'spid' => '12345678'
                ],
                'form_params' => [
                    "disco" =>  "Pay Electricity aedc",
                    "meter_number" =>  "30530035598",
                    "payment_type"=> "prepaid",
                    "msisdn"=> "08034910941",
                    "amount"=> "200",
                    "action" => "electricity_verify"
                ]
            ]
        );

        if ($response->getStatusCode() == 200) {
            dd($response->getBody()->getContents());

            echo $response->getBody();

        } else {

            echo 'Unexpected HTTP status: ' . $response->getStatusCode() . ' ' .
            $response->getReasonPhrase();

        }
    }
    catch(HTTP_Request2_Exception $e) {

        echo 'Error: ' . $e->getMessage();

    }
});

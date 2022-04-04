<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;

class Sms {

    protected $AT;

    function __construct() {
        $this->AT = new AfricasTalking(env('API_USERNAME'), env('API_KEY'));
    }


    public function sendSms($message, $recipients) {

        //get the sms service
        $sms = $this->AT->sms();

        //use the SMS service to send SMS
        $result = $sms->send([
            'to'      => $recipients,
            'message' => $message,
            'from'    => env('COMPANY_NAME')
        ]);

        return $result;
    }
}

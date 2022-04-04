<?php

namespace App\Services;

use App\Models\Customer;
use App\Repositories\CustomerRepostoryInterface;

class Util {

    protected $text;
    protected $sessionId;
    protected $serviceCode;
    protected $phoneNumber;

    public function __construct() {

        $this->sessionId   = request('sessionId');
        $this->serviceCode = request('serviceCode');
        $this->phoneNumber = request('phoneNumber');
        $this->text        = request('text');
    }

    /**
     * Chech if user is registered
     *
     * @param  mixed $phoneNumber
     * @return boolean
     */
    public function isUserRegistered() {

        $customer = Customer::where('phone_number', $this->phoneNumber)->first();

        return $customer ? true : false;
    }


    /**
     * Register a user
     *
     * @param  mixed $phoneNumber
     * @param  mixed $meterNumber
     * @return void
     */
    public function registeredUser($meterNumber, $pin) {

        // if this meter_number exists, update its phone number
        $result = Customer::where('meter_number', $meterNumber)->first();

        if($result) {

            $customer = $result->update([
                'phone_number' => $this->phoneNumber,
                'pin' => $pin,
            ]);

        } else {

            $customer = Customer::create([
                'meter_number' => $meterNumber,
                'phone_number' => $this->phoneNumber,
                'pin' => $pin,
            ]);

        }

        return $customer;

    }
}

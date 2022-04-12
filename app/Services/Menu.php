<?php

namespace App\Services;

use App\Services\Sms;
use App\Models\Customer;
use App\Models\Transaction;
use App\Events\PowerWasPurchased;
use App\Repositories\CustomerRepostoryInterface;

class Menu{

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

    public function mainMenuRegistered($name){
        //shows initial user menu for registered users
        $response = "Welcome " . $name . " Reply with\n";
        $response .= "1. Outright\n";
        $response .= "2. Buy Now Pay Later\n";
        return $response;
    }

    public function mainMenuUnRegistered() {
        //shows initial user menu for unregistered users
        $response = "CON Welcome to this COFFERS. Reply with\n";
        $response .= "1. Register\n";
        echo $response;
    }

    public function registerMenu($textArray, $phoneNumber) {

        //building menu for user registration
        $level = count($textArray);
        if($level == 1){

            echo "CON Please enter your Meter number:";

        } else if ($level == 2) {

            echo "CON Please enter set you PIN:";

        } else if ($level == 3) {

            echo "CON Please re-enter your PIN:";

        } else if ($level == 4) {

            $meterNumber = $textArray[1];
            $pin = $textArray[2];
            $confirmPin = $textArray[3];

            if ($pin != $confirmPin) {

                echo "END Your pins do not match. Please try again";

            } else {

                //connect to DB and register a user.
                $customer = (new Util())->registeredUser($meterNumber, $pin);

                echo "END You have been registered";
                $sms = new Sms();
                $message = "You have been registered";
                $sms->sendSms($message,$phoneNumber);

            }
        }
    }

    public function outrightPurchase($textArray){
        //building menu for user registration
        $level = count($textArray);
        $response = "";


        if ($level == 1){ // AMOUNT

            echo "CON Enter amount:";

        } else if ($level == 2) { // PIN

            $customer = Customer::where('phone_number', $this->phoneNumber)->first();

            $response .= "Buy " . number_format($textArray[1]) . " on Meter: " . $customer->meter_number . "\n";
            $response .= "Enter your PIN to confirm:\n";
            $response .= "2. Cancel\n";
            $response .= env('GO_BACK') . " Back\n";
            $response .= env('GO_TO_MAIN_MENU') .  " Main menu\n";

            echo "CON " . $response;

        // } else if ($level == 3) {

        //     $customer = Customer::where('phone_number', $this->phoneNumber)->first();

        //     $response .= "Buy " . number_format($textArray[1]) . " on Meter - " . $customer->meter_number . "\n";
        //     $response .= "1. Confirm\n";
        //     $response .= "2. Cancel\n";
        //     $response .= env('GO_BACK') . " Back\n";
        //     $response .= env('GO_TO_MAIN_MENU') .  " Main menu\n";
        //     echo "CON " . $response;

        } else if ($level == 3 && $textArray[2] != 2) {

            //check if PIN correct
            $amount = $textArray[1];
            $pin = $textArray[2];
            $customer = Customer::where('phone_number', $this->phoneNumber)->first();

            if ($pin != $customer->pin) {

                echo "END Incorrect PIN";

            } else {

                //connect to DB
                $transaction = Transaction::create([
                    'customer_id' => $customer->id,
                    'amount' => $amount,
                ]);

                // fire event
                event(new PowerWasPurchased($transaction));

                echo "END We are processing your request. You will receive an SMS shortly";
            }

        } else if ($level == 3 && $textArray[2] == 2) {

            //Cancel
            echo "END Canceled. Thank you for using our service";

        } else if ($level == 3 && $textArray[2] == env('GO_BACK')) {

            echo "END You have requested to back to one step - re-enter PIN";

        } else if ($level == 4 && $textArray[2] == env('GO_TO_MAIN_MENU')) {

            echo "END You have requested to back to main menu - to start all over again";

        } else {

            echo "END Invalid entry";

        }
    }

    public function bnpl($textArray){
        //building menu for user registration
        $level = count($textArray);
        $response = "";


        if ($level == 1) {

            $customer = Customer::where('phone_number', $this->phoneNumber)->first();
            $eligibleAmount = $this->eligibleAmount($customer);

            $response .= "You are eligible for N".number_format($eligibleAmount);
            $response .=  "\n Enter desired amount:";
            echo "CON ". $response;

        } else if ($level == 2) {

            $amount = $textArray[1];
            $customer = Customer::where('phone_number', $this->phoneNumber)->first();
            $eligibleAmount = $this->eligibleAmount($customer);

            if ($this->confirmBnplAmount($amount, $eligibleAmount)) {

                echo "END Amount exceeds: ".number_format($eligibleAmount);

            } else {

                echo "CON Enter your PIN:";

            }


        } else if ($level == 3) {

            $customer = Customer::where('phone_number', $this->phoneNumber)->first();

            $response .= "Buy " . number_format($textArray[1]) . " on Meter - " . $customer->meter_number . "\n";
            $response .= "1. Confirm\n";
            $response .= "2. Cancel\n";
            $response .= env('GO_BACK') . " Back\n";
            $response .= env('GO_TO_MAIN_MENU') .  " Main menu\n";
            echo "CON " . $response;

        } else if ($level == 4 && $textArray[3] == 1) {

            //check if PIN correct
            $amount = $textArray[1];
            $pin = $textArray[2];
            $customer = Customer::where('phone_number', $this->phoneNumber)->first();


            // check pin
            if ($pin != $customer->pin) {

                echo "END Incorrect PIN";

            } else {

                // confirm amount entered
                $eligibleAmount = $this->eligibleAmount($customer);
                if ($this->confirmBnplAmount($amount, $eligibleAmount)) {

                    echo "END Amount exceeds: ".number_format($eligibleAmount);

                } else {

                    //connect to DB
                    $transaction = Transaction::create([
                        'customer_id' => $customer->id,
                        'amount' => $amount,
                    ]);

                    // fire event
                    event(new PowerWasPurchased($transaction));

                    echo "END We are processing your request. You will receive an SMS shortly";
                }

            }

        } else if ($level == 5 && $textArray[3] == 2) {

            //Cancel
            echo "END Canceled. Thank you for using our service";

        } else if ($level == 5 && $textArray[3] == env('GO_BACK')) {

            echo "END You have requested to back to one step - re-enter PIN";

        } else if ($level == 5 && $textArray[3] == env('GO_TO_MAIN_MENU')) {

            echo "END You have requested to back to main menu - to start all over again";

        } else {

            echo "END Invalid entry";

        }
    }

    public function addCountryCodeToPhoneNumber($phone){
        return env('COUNTRY_CODE') . substr($phone, 1);
    }

    public function middleware($text){
        //remove entries for going back and going to the main menu
        return $this->goBack($this->goToMainMenu($text));
    }

    public function goBack($text){
        //1*4*5*1*98*2*1234
        $explodedText = explode("*",$text);

        while(array_search(env('GO_BACK'), $explodedText) != false) {

            $firstIndex = array_search(env('GO_BACK'), $explodedText);
            array_splice($explodedText, $firstIndex-1, 2);

        }

        return join("*", $explodedText);
    }

    public function goToMainMenu($text){
        //1*4*5*1*99*2*1234*99
        $explodedText = explode("*",$text);

        while(array_search(env('GO_TO_MAIN_MENU'), $explodedText) != false) {

            $firstIndex = array_search(env('GO_TO_MAIN_MENU'), $explodedText);
            $explodedText = array_slice($explodedText, $firstIndex + 1);

        }

        return join("*",$explodedText);
    }

    private function eligibleAmount(Customer $customer) {
        // get this via coffers ENDPOINT
        return 2000;
    }

    private function confirmBnplAmount($amountEntered, $eligibleAmount) {
        return intval($amountEntered) > intval($eligibleAmount) ? true : false;
    }
}

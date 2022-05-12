<?php

namespace App\Http\Controllers;

use App\Services\Menu;
use App\Services\Util;
use Illuminate\Http\Request;
use App\Repositories\CustomerRepostoryInterface;

class CustomerController extends Controller
{

    public function index() {

        // Read the data sent via POST from our AT API
        $sessionId   = request('sessionId');
        $serviceCode = request('serviceCode');
        $phoneNumber = request('phoneNumber');
        $text        = request('text');


        // check if user is registered
        $isUserRegistered = (new Util())->isUserRegistered();


        $menu = new Menu();
        $text = $menu->middleware($text);
        //$text = $menu->goBack($text);

        if ($text == "" && $isUserRegistered) {

            //user is registered and string is empty
            echo "CON " . $menu->mainMenuRegistered("");

        } else if ($text == "" && $isUserRegistered == false) {

            //user is unregistered and string is is empty
            $menu->mainMenuUnRegistered();

        } else if ($isUserRegistered == false) {

            //user is unregistered and string is not empty
            $textArray = explode("*", $text);

            switch($textArray[0]){
                case 1:
                    $menu->registerMenu($textArray, $phoneNumber);
                    break;
                default:
                    echo "END Invalid choice. Please try again";
            }

        } else {

            //user is registered and string is not empty
            $textArray = explode("*", $text);
            switch($textArray[0]) {

                case 1:
                    $menu->outrightPurchase($textArray,$sessionId);
                break;

                case 2:
                    $menu->bnpl($textArray);
                break;

                default:
                    echo "END Inavalid menu\n";
            }

        }

    }
}

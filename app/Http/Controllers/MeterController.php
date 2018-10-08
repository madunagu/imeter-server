<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use JWTAuth;
use App\Meter;

/* this is for requests from the web application to the meter
*/

class MeterController extends Controller
{
    public function toggleOn(Request $request)
    {
        #here verify the user that made this request
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $meter = $user->meter();
        $meter->toggleOn();
    }

    public function rechargeMeter(Request $request)
    {
        $amount = $request['amount'];
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $meter = $user->meter();
        $meter->recharge($amount);
    }

    public function setEnergyBalance(Request $request)
    {
        $amount = $request['amount'];
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $meter = $user->meter();
        $meter->setEnergyBudget($balance);
    }

    public function sendIOTData(Request $request){
        #here we add the complex logic concerning iot data structures
        $data = $request['data'];
        $token = JWTAuth::getToken();
        $user = JWTAuth::toUser($token);
        $meter = $user->meter();
        $meter->pushIOTData($data);
    }
}

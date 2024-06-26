<?php

namespace App\Http\Controllers;

use App\Custom\SwissKnife;
use App\Meter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\EnergyBudget;
use App\ServerRequest;

/* this is for requests from the web application to the meter
*/

class MeterController extends Controller
{
    public function toggleOn(Request $request)
    {
        #here verify the user that made this request
        $user = Auth::user();
        $meter = Meter::findByUser($user);
        $meter->toggleOn();
    }

    public function toggleOff(Request $request)
    {
        #here verify the user that made this request
        $user = Auth::user();
        $meter = Meter::findByUser($user);
        $meter->toggleOff();
    }

    public function rechargeMeter(Request $request)
    {
        $amount = $request['amount'];
        $user = Auth::user();
        $meter = Meter::findByUser($user);
        $meter->recharge($amount);
    }

    public function setEnergyBudget(Request $request)
    {
        $amount = $request['amount'];
        $enforcement = $request['enforcement'];
        $user = Auth::user();
        $meter = Meter::findByUser($user);
        $energy_budget = new EnergyBudget();
        $energy_budget->meter_id = $meter->id;
        $energy_budget->energy_budget = SwissKnife::ensurePositive($amount);
        $energy_budget->enforcement = $enforcement;
        $energy_budget->save();

        $server_request = new ServerRequest();
        $server_request->meter_id = $meter->id;
        $server_request->request_type = ServerRequest::$requestTypeEnergyBudget;
        $server_request->request_key = ServerRequest::$requestKeyEnergyBudget;
        $server_request->request_value = $energy_budget->energy_budget;
        $server_request->save();

        return response()->json(['success'=>true,['succesfully set Energy Budget']],200);
    }

    public function sendIOTData(Request $request){
        #here we add the complex logic concerning iot data structures
        $data = $request['data'];
        $user = Auth::user();
        $meter = Meter::findByUser($user);
        $meter->pushIOTData($data);
    }
}

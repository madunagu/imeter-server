<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Custom\SwissKnife;
use App\CalibrationData;
use App\Meter;

class CalibrationController extends Controller
{
    //
    public function bootUp(Request $request){
        $parameters = $request['Password'];
        try{
            $calibrationJSON = \json_decode($parameters);
            $phone = $calibrationJSON->SIM;
            $meter_type_id = $calibrationJSON->MT;
            $sys = $calibrationJSON->sys;
            $cal = $calibrationJSON->cal;

        }
        catch(\Exception $e){
            SwissKnife::reportError("validation failed");
        }

        $meter = new Meter();
        #generate a unique new meter number
        $meter->number = Meter::generateMeterNumber();
        $meter->balance = 0;
        $meter->phone = $phone;
        $meter->meter_type_id = $meter_type_id;
        $meter->user_id = 0;

        $calibrationData = new CalibrationData();
        $calibrationData->sys = $sys;
        $calibrationData->cal = $cal;
        $calibrationData->save();

        $meter->save();

        $response = SwissKnife::respond(9,$meter->number);

        echo $output = SwissKnife::output($response,$meter->number);

    }
}

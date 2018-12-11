<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Custom\SwissKnife;
use App\CalibrationData;
use App\Meter;
use App\User;
use App\EncryptionKey;
use App\Http\Resources\User as UserResource;
use App\FOTAUpdate;
use Validator;

class BootUpController extends Controller
{
    //
    public function bootUp(Request $request)
    {
        $parameters = $request['Password'];
        $calibrationJSON = \json_decode($parameters);
        $validator = Validator::make((array) $calibrationJSON, [
            'SIM' => 'required',
            'MT' => 'required',
            'sys'=>'required',
            'cal'=>'required',
            'ID'=>'required'
        ]);

        if ($validator->fails()) {
            return SwissKnife::reportError($validator->errors());
        }
        $phone = $calibrationJSON->SIM;
        $meter_type_id = $calibrationJSON->MT;
        $sys = $calibrationJSON->sys;
        $cal = $calibrationJSON->cal;

        $meter = new Meter();
        #generate a unique new meter number
        $meter->number = Meter::generateMeterNumber();
        $meter->balance = 0;
        $meter->phone = $phone;
        $meter->meter_type_id = $meter_type_id;
        $meter->user_id = 0;
        $meter->disco_id = 0;
        $meter->chip_id = $calibrationJSON->ID;

        $calibrationData = new CalibrationData();
        $calibrationData->sys = $sys;
        $calibrationData->cal = $cal;
        $calibrationData->save();

        $meter->save();

        $response = SwissKnife::respond(9, $meter->number);

        return $output = SwissKnife::output($response, $meter->number);
    }


    public function homeSetup(Request $request)
    {
        $parameters = $request['Password'];
        try {
            $calibrationJSON = \json_decode($parameters);
            $meter_number = $calibrationJSON->MN;
            $meter_chip_id = $calibrationJSON->ID;
            $message_type = $calibrationJSON->Msg;
            $meter_type = $calibrationJSON->MT;
        } catch (\Exception $e) {
            SwissKnife::reportError("validation failed");
        }

        $meter = Meter::where('meter_number', $meter_number)->first();
        #generate a unique new meter number
        if (!($meter->meter_type_id == $meter_type && $meter->chip_id == $meter_chip_id && $meter->user_id !=0)) {
            # if the meter data is wrong
            die('meter data is wrong or meter is unassigned');
        }

        $user = User::where('id',$meter->user_id)->first();

        $encryptionKeySet = new EncryptionKey();
        $encryptionKeySet->meter_id = $meter->id;
        $encryptionKeySet->input_key = \str_random(10);
        $encryptionKeySet->output_key = \str_random(10);
        $encryptionKeySet->save();

        $version = FOTAUpdate::orderBy('id','DESC')->first();

        $response = SwissKnife::respond(9, $meter->number);
        $response->user = new UserResource($user);
        $response->SWV = $version->id;
        $response->KeyIn = $encryptionKeySet->input_key;
        $response->KeyOut = $encryptionKeySet->output_key;

        echo $output = SwissKnife::output($response, $meter->number);
    }
}

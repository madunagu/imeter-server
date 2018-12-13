<?php

namespace App\Http\Controllers;

use App\MeterStatistics;
use Illuminate\Http\Request;
use App\DailyUsage;
use App\HourlyUsage;
use App\Meter;
use App\Custom\SwissKnife;
use App\ServerRequest;
use App\Warning;
use App\Tamper;
use App\MeterRequest;
use App\EnergyBudget;
use Illuminate\Support\Carbon;
use Validator;

class CollectorController extends Controller
{
    //
    public function collect(Request $request)
    {
        $parameters = $request['Password'];
        try {
            $params = \json_decode($parameters);
        } catch (\Exception $e) {
            die("invalid data format");
        }
        $validator = Validator::make((array) $params, [
            'MN' => 'required',
            'Msg' => 'required',
            'CT'=>'required',
            'S'=>'required',
        ]);

        //TODO: add future time verification failure

        if ($validator->fails()) {
            return SwissKnife::reportError($validator->errors());
        }
        $meter_no = $params->MN;
        $message_type = $params->Msg;
        $date = $params->CT;
        $sent_time = $params->S;

        $base_message_type = substr($params->Msg, 0, 1);
        $myReq = new MeterRequest();
        $myReq->meter_number = $meter_no;
        $myReq->message_type = $message_type;
        $myReq->sent_time = $sent_time;
        $myReq->recieved_time = time();
        $myReq->body = $parameters;
        $myReq->time_lag = (int)$myReq->recieved_time - (int)$sent_time;
        $myReq->save();


        #here verify meter parameters and decrypt
        $meter = Meter::where('number', $meter_no)->first();
        if (!$meter) {
            die('unidentified meter');
        }

        switch ($base_message_type) {
            case 1:
                $validator = Validator::make((array) $params, [
                    'H' => 'required',
                    'Bal' => 'required',
                    'WH'=>'required',
                    'Cost'=>'required',
                ]);

                if ($validator->fails()) {
                    return SwissKnife::reportError($validator->errors());
                }
                $hour = $params->H;
                $balance = $params->Bal;
                $usage = $params->WH;
                $cost = $params->Cost;

                #here update the meter balance
                $meter->balance = empty($balance)? $meter->balance : $balance;
                $meter->save();

                $hourly_usage = HourlyUsage::make_and_save($meter, (int)$date, $usage, $cost, $hour);

                EnergyBudget::compareUsage($meter->id);

                $result = SwissKnife::respond($message_type, $meter_no);

                return SwissKnife::output($result, $meter_no);
                break;

            case 2:
                $pending = ServerRequest::where('meter_id', $meter->id)->where('done_time', 0)->orderBy('created_at', 'desc')->first();
                $result = SwissKnife::respond($message_type, $meter_no);
                if (!empty($pending)) {
                    $key = $pending->request_key;
                    $value = $pending->request_value;
                    $result->$key = $value;
                    $result->PAR = $pending->request_type;

                    # update the done time
                    $pending->done_time = time();
                    $pending->save();
                }

                return SwissKnife::output($result, $meter_no);
                break;

            case 3:
                $validator = Validator::make((array) $params, [
                    'WT' => 'required',
                    'Val' => 'required',
                ]);

                if ($validator->fails()) {
                    return SwissKnife::reportError($validator->errors());
                }
                $type = $params->WT;
                $val = $params->Val;
                $max = 100; //$params->Max;
                $result = SwissKnife::respond($message_type, $meter_no);


                $warning = new Warning();
                $warning->collected_date = $date;
                $warning->meter_number = $meter_no;
                $warning->warning_type = $type;
                $warning->warning_value = $val;
                $warning->warning_max = $max;
                $warning->save();

                return SwissKnife::output($result, $meter_no);
                break;

            case 4:
                $validator = Validator::make((array) $params, [
                    'T' => 'required',
                ]);

                if ($validator->fails()) {
                    return SwissKnife::reportError($validator->errors());
                }
                $type = $params->T;
                $theft = new Tamper();
                $theft->meter_id = $meter->id;
                $theft->tamper_type = $type;
                $theft->collected_date = $date;
                $theft->save();
                $result = SwissKnife::respond($message_type, $meter_no);
                return SwissKnife::output($result, $meter_no);
                break;

            case 8:
                #regular meter readings
                #here save the meter readings

                $validator = Validator::make((array) $params, [
                    'Bal' => 'required',
                    'WH' => 'required',
                    'temp'=>'required',
                    'On'=>'required',
                    'AT'=>'required',
                    'P'=>'required',
                    'Vac'=>'required',
                    'A'=>'required',
                    'Vac'=>'required',
                    'Hz'=>'required',
                    'Bat'=>'required',
                ]);

                if ($validator->fails()) {
                    return SwissKnife::output($validator->errors(), $meter->number);
                }

                $balance = $params->Bal;
                $usages = $params->WH;
                $costs =  $params->Cost;
                foreach ($usages as $key => $usage) {
                    $hourly_usage = HourlyUsage::make_and_save($meter, (int)$date, $usage, $costs[$key], $key);
                }
                #here save other regular meter data
                $meterStat = new MeterStatistics();
                $meterStat->meter_id = $meter->id;
                $meterStat->average_temprature = $params->temp;
                $meterStat->naira_balance = $params->Bal;
                # the last item in the array will tell us if the meter is on or off
                $meterStat->connect_status = end($params->On)[0];
                $meterStat->airtime = $params->AT;
                $meterStat->energy_balance = $params->P;
                $meterStat->average_voltage = $params->Vac;
                $meterStat->average_current = $params->A;
                $meterStat->average_frequency = $params->Hz;
                $meterStat->battery_level = $params->Bat;
                $meterStat->collected_date = $date;
                $meterStat->save();

                #here update the meter balance
                $meter->balance = empty($balance)? $meter->balance : $balance;
                $meter->save();
                EnergyBudget::compareUsage($meter->id);
                $result = SwissKnife::respond($message_type, $meter_no);

                return SwissKnife::output($result, $meter_no);
                break;
        }
    }
}

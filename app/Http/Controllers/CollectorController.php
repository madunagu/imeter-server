<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use App\DailyUsage;
use App\HourlyUsage;
use App\Meter;
use App\Custom\SwissKnife;
use App\ServerRequest;
use App\Warning;
use App\Tamper;
use App\MeterRequest;
use App\EnergyBudget;

class CollectorController extends Controller
{
    //
    public function collect(Request $request)
    {
        $parameters = $request['Password'];
        try {
            $params = json_decode($parameters);
        } catch (Exception $e) {
            die("invalid data format");
        }
        try {
            $meter_no = $params->MN;
            $message_type = $params->Msg;
            $date = $params->DT;
            $sent_time = $params->S;
        }
        catch(Exception $e){
            die("invalid format required parameters such as collected date, meter number or message type may be missing");
        }
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
        $hour = $params->H;
        $balance = $params->Bal;
        $usage = $params->WH;

        #here update the meter balance
        $meter->balance = empty($balance)? $meter->balance : $balance;
        $meter->save();

        $hourly_usage = HourlyUsage::make_and_save($meter_no, (int)$date, $usage, $hour, true);

        EnergyBudget::compareUsage($meter->id);

        $result = SwissKnife::respond($message_type, $meter_no);

         return SwissKnife::output($result, $meter_no);
        break;

        case 2:
        $pending = ServerRequest::where('meter_id', $meter->id)->where('done', 0)->orderBy('created_at', 'desc')->first();
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
        $type = $params->WT;
        $val = $params->Val;
        $max = $params->Max;
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
        $type = $params->T;
        $theft = new Tamper();
        $theft->meter_id = $meter->id;
        $theft->tamper_type = $type;
        $theft->collected_date = $date;
        $theft->save();
        $result = SwissKnife::respond($message_type,$meter_no);
        return SwissKnife::output($result,$meter_no);
        break;

        case 8:
        #regular meter readings
        #here save the meter readings
        $day = Carbon::createFromTimestamp($params->date)->day;
        $daily_usage = DailyUsage::make_and_save($meter_no, (int)$date, $usage, $day);

        #here save other regular meter data


        $result = SwissKnife::respond($message_type, $meter_no);

        return SwissKnife::output($result, $meter_no);
        break;
        }
    }
}

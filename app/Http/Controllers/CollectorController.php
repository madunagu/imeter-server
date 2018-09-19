<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mockery\CountValidator\Exception;
use App\DailyUsage;
use App\HourlyUsage;
use App\Meter;
use App\Custom\SwissKnife;

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
        $meter_no = $params->MN;
        $message_type = $params->Msg;
        $base_message_type = substr($params->Msg, 0, 1);

        #here verify meter parameters and decrypt
        $meter = Meter::where('number', $meter_no)->first();
        if(!$meter){
            die('unidentified meter');
        }

        switch ($base_message_type) {
        case 1:
        $hour = $params->H;
        $balance = $params->Bal;
        $date = $params->DT;
        $usage = $params->WH;

        #here update the meter balance
        $meter->balance = empty($balance)? $meter->balance : $balance;
        $meter->save();

        $hourly_usage = HourlyUsage::make_and_save($meter_no, (int)$date, $usage, $hour,true);

        $result = SwissKnife::respond($message_type,$meter_no);

        SwissKnife::output($result,$meter_no);
        break;

        case 2:
        break;

        case 3:
        $result = SwissKnife::respond($message_type,$meter_no);

        SwissKnife::output($result,$meter_no);
        break;

        case 8:
        #regular meter readings
        #here save the meter readings
        $day = Carbon::createFromTimestamp($params->date)->day;
        $daily_usage = DailyUsage::make_and_save($meter_no, (int)$date, $usage, $day);

        #here save other regular meter data
        

        $result = SwissKnife::respond($message_type,$meter_no);

        SwissKnife::output($result,$meter_no);
        break;
        }
    }
}

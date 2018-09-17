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
        $base_message_type = substr($params->Msg, 0, 1);
        $date = $params->DT;
        $usage = $params->WH;

        switch ($base_message_type) {
        case 1:
        $hour = $params->H;
        $balance = $params->Bal;
        
        #here verify meter parameters and decrypt

        $hourly_usage = HourlyUsage::make_and_save($meter_no, (int)$date, $usage, $hour);

        #here update the meter balance
        $meter = Meter::where('number', $meter_no);
        $meter->balance = empty($balance)? $meter->balance : $balance;
        # $meter->save();

        $hourly_usage->save();

        $result = SwissKnife::object();
        $result->MN = $meter_no;
        $result->Msg = $base_message_type;
        $result->S = time();

        echo json_encode($result);
        break;

        case 8:
        #regular meter readings
        #here save the meter readings
        $day = Carbon::createFromTimestamp($params->date)->day;
        $daily_usage = DailyUsage::make_and_save($meter_no, (int)$date, $usage, $day);
        break;
        }
    }
}

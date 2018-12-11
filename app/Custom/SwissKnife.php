<?php
namespace App\Custom;

class SwissKnife
{
    public static function respond($message_type, string $meter_no)
    {
        $result = new \stdClass();
        $result->MN = $meter_no;
        $result->Msg = $message_type;
        $result->S = time();
        return $result;
    }

    public static function output($result, string $meter_no)
    {
        return response()->json($result);
    }

    public static function encode($object)
    {
        return response()->json($object);
    }

    public static function throw_403($error)
    {
    }

    public static function ensurePositive($number)
    {
        return abs($number);
    }

    public static function reportError($error)
    {
        return response()->json($error);
    }

    public static function randMeterNo()
    {
        return \str_random(10);
    }
}

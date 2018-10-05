<?php
namespace App\Custom;

class SwissKnife
{
    public static function respond($message_type,string $meter_no)
    {
        $result = new \stdClass();
        $result->MN = $meter_no;
        $result->Msg = $message_type;
        $result->S = time();
        return $result;
    }

    public static function output($result, string $meter_no)
    {
        echo json_encode($result);
    }

    public static function encode($object){
        echo json_encode($object);
    }

    public static function throw_403($error){

    }
}

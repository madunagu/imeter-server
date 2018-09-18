<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\meter;
class Usage extends Model
{

    public static function make_and_save(string $meter_number, int $date, $WH, $hour=24)
    {
        $usage = new static();
        $usage->meter_number = $meter_number;
        $usage->collected_date = $date;
        $usage->hour = $hour;
        #get the tarrif then set it
        $usage->tarrif = 14;
        $usage->setUsages($WH);
        $usage->process();
        $usage->set_parent_id();
        $usage->save_or_not();
        return $usage;
    }

    public function get_last_usage()
    {
        $last_usage = 0;
        $last = static::where('meter_number', $this->meter_number)->orderBy('collected_date','desc')->first();
        if ($last) {
            $last_usage = $last->usage;
        }
        return $last_usage;
    }

    public function setUsages($WH)
    {
        $this->usage = $WH;
    }

    public function set_parent_id(){
    }

    public function save_or_not(){

    }

    public function get_tarrif(){
        $meter = Meter::where('meter_number',$this->meter_number);
    }

    public function c_time(){
       return  Carbon::createFromTimestamp($this->collected_date);
    }

    public function process()
    {
        $this->cost = $this->usage * $this->tarrif;
        $last_usage = $this->get_last_usage();
        $this->change = abs($this->usage - $last_usage);
        $this->delta = ($this->usage- $last_usage)>0;
    }
}

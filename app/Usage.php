<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    public $meter_id;
    public $date;
    public $tarrif = 14;

    public static function make($meter_id, string $date, $usage, $hour=24)
    {
        $usage = new static();
        $usage->meter_id = $meter_id;
        $usage->date = $date;
        $usage->setUsages($usage);
        $usage->process();
        $usage->set_parent_id();
        $usage->save_or_not();
        return $usage;
    }

    public function get_last_usage()
    {
        $last_usage = 0;
        $last = static::where('meter_id', $this->meter_id)->orderBy('collected_date','desc')->first();
        if ($last) {
            $last_usage = $last->usage;
        }
        return $last_usage;
    }

    public function setUsages($usage)
    {
        $this->usage = $usage;
    }

    public function set_parent_id(){
    }

    public function save_or_not(){

    }

    public function process()
    {
        $this->cost = $this->usage * $this->tarrif;
        $last_usage = $this->get_last_usage();
        $this->change = abs($this->usage - $last_usage);
        $this->delta = ($this->usage- $last_usage)>0;
        $this->c_time =  Carbon::createFromTimestamp($this->date);
    }
}

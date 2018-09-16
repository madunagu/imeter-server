<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usage extends Model
{
    public $meter_id;
    public $date;
    public $tarrif = 14;

    public function __construct(int $meter_id, string $date, $usage, $hour=24)
    {
        $this->meter_id = $meter_id;
        $this->date = $date;
        $this->setUsages($usage);
        $this->process();
        $this->set_parent_id();
        $this->save_or_not();
    }

    public function get_last_usage()
    {
        $last_usage = 0;
        $last = static::where('meter_id', $this->meter_id)->desc()->first();
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

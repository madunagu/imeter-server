<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Meter;

class Usage extends Model
{
    public $make_children;
    public $make_parent;

    public static function make_and_save(int $meter_id, int $date, $WH, $value = 24, bool $make_children = false, $make_parent = false)
    {
        $usage = new static();
        $usage->meter_id = $meter_id;
        $usage->collected_date = $date;
        $usage->{static::$key} = $value;
        #get the tarrif then set it
        $usage->tarrif = $usage->get_tarrif();
        $usage->make_children = $make_children;
        $usage->make_parent = $make_parent;
        $usage->setUsages($WH);
        $usage->process();
        $usage->set_parent_id();
        $usage->save_or_not();
        return $usage;
    }

    public function get_last_usage()
    {
        $last_usage = 0;
        $last_two = static::where('meter_id', $this->meter_id)->orderBy('collected_date', 'desc')->take(2)->get();
        if (!empty($last_two) && !empty($last_two[0]) && $last_two[0] != $this) {
            $last = $last_two[0];
        }
        if (!empty($last_two) && !empty($last_two[1])) {
            $last = $last_two[1];
        }
        if (!empty($last)) {
            $last_usage = $last->usage;
        }
        return $last_usage;
    }

    public function setUsages($WH)
    {
        $this->usage = $WH;
    }

    public function set_parent_id()
    {

    }
    public function save_or_not()
    {
        #if it is not duplicate save it
        $this->save();
    }

    public function get_tarrif()
    {
        $meter = Meter::find($this->meter_id);
        if ($meter) {
            #that meter is verified continue
            return $meter->tarrif;
        } else {
//            #that is an unverified meter lets know the meter_no
//            $unverified = new UnVerified();
//            $unverified->meter_number = $this->meter_number;
//            $unverified->save();
//
//            die('unidentified meter');
        }
    }

    public function c_time()
    {
        return Carbon::createFromTimestamp($this->collected_date);
    }

    public function process()
    {
        $this->cost = $this->usage * $this->tarrif;
        $last_usage = $this->get_last_usage();
        $this->delta = abs($this->usage - $last_usage);
        #delta is a boolean if the change is positive or negative
        $this->down = ($this->usage - $last_usage) < 0;
    }

    public function update_usage()
    {
    }

    public function calculate_params($children){
        if(count($children)==0){
            return;
        }
        $total_usage = 0;
        $total_cost = 0;
        $total_tarrif = 0;
        foreach ($children as $child) {
            $total_cost += $child->cost;
            $total_usage += $child->usage;
            $total_tarrif+=$child->tarrif;
        }
        $this->cost = $total_cost;
        $this->usage = $total_usage;
        $this->tarrif = round($total_tarrif/count($children),3);
        $last_usage = $this->get_last_usage();
        $this->delta = abs($this->usage - $last_usage);
        #delta is a boolean if the change is positive or negative
        $this->down = ($this->usage - $last_usage) < 0;
        $this->save();
    }
}

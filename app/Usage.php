<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Meter;

class Usage extends Model
{
    public static function make_and_save(Meter $meter, int $date, float $WH, float $cost = 0, int $value = 24)
    {
        $usage = new static();
        $usage->meter_id = $meter->id;
        $usage->collected_date = $date;
        $usage->{static::$key} = $value;
        #set usage
        $usage->usage = $WH;
        #set cost
        $usage->cost = $cost;
        #get the tarrif then set it
        $tarrif = $usage->get_tarrif($meter);
        if (static::class==HourlyUsage::class) {
            $usage->tarrif = $tarrif[$value];
            if ($usage->cost!=($usage->usage * $usage->tarrif)) {
                #here logic for thowing error of different costs
            }
        }
        $usage->process($tarrif);
        $usage->set_parent_id($meter);
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

    public function set_parent_id(Meter $meter)
    {
    }
    public function save_or_not()
    {
        #if it is not duplicate save it
        $this->save();
    }

    public function get_tarrif(Meter $meter):array
    {
        #get the array of TOU for that meter
        return TimeOfUse::getArray($meter);
    }

    public function c_time()
    {
        return Carbon::createFromTimestamp($this->collected_date);
    }

    public function process()
    {
        $last_usage = $this->get_last_usage();
        $this->delta = abs($this->usage - $last_usage);
        #delta is a boolean if the change is positive or negative
        $this->down = ($this->usage - $last_usage) < 0;
    }

    public function update_usage()
    {
    }

    public function calculate_params($children)
    {
        if (count($children)==0) {
            return;
        }
        $total_usage = 0;
        $total_cost = 0;
        foreach ($children as $child) {
            $total_cost += $child->cost;
            $total_usage += $child->usage;
        }
        $this->cost = $total_cost;
        $this->usage = $total_usage;
        $last_usage = $this->get_last_usage();
        $this->delta = abs($this->usage - $last_usage);
        #delta is a boolean if the change is positive or negative
        $this->down = ($this->usage - $last_usage) < 0;
        $this->save();
    }
}

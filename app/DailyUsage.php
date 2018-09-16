<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Usage;
use App\MonthlyUsage;

class DailyUsage extends Usage
{
    public $meter_id;
    public $tarrif = 14;


    public function setUsages(array $usages = [])
    {
        $total = 0;
        $hour = 0;
        foreach ($usages as $usage) {
            #here create all the hourly usages
            $total += $usage;
            $hourly = new HourlyUsage($this->meter_id, $this->date, $usage, $hour);
            $hour++;
        }
        $this->usage = $total;
    }

    public function set_parent_id()
    {
        $monthly = MonthlyUsage::where('meter_id', $this->meter_id)->orderBy('collected_date', 'desc')->first();
        $monthly_date = Carbon::createFromTimestamp($monthly->collected_date);
        if ($daily_date->isSameDay($this->c_time)) {
            $this->monthly_usage_id = $daily->id;
            #set the parent id and do not create a new parent
            return;
        }
        #if there is no monthly yet create one
        $monthly = new MonthlyUsage($this->meter_id, $this->date, $this->usage, $this->c_time->month);
        $monthly->save();
        $this->monthly_usage_id = $monthly->id;
    }

    public function save_or_not()
    {
        #here check if it is a duplicate
        $old = static::where('meter_id', $this->meter_id)->orderBy('collected_date', 'desc')->first();
        $old_date = Carbon::createFromTimestamp($old->collected_date);
        if ($old_date->isSameDay($this->c_time)) {
            #if they are duplicate warn us of duplicates
            $redundant = new RedundantUsage();
            $redundant->name = static::class;
            $redundant->usage_id = $old->id;
            $redundant->save();
            # do not continue execution
            return;
        }
        #if it is not duplicate save it
        $this->save();
    }
}

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


    public function setUsages($usages)
    {
        $total = 0;
        $hour = 0;
        #if it is not an array

        #if the usages is an array
        if (is_array($usages)) {
            foreach ($usages as $usage) {
                #here create all the hourly usages
                $total += $usage;
                #noticed presence of infinite loop
                #$hourly = new HourlyUsage($this->meter_id, $this->date, $usage, $hour);
                $hour++;
            }
        } else {
            #noticed the same
            #$hourly = new HourlyUsage($this->meter_id, $this->date, $usages, $hour);
            $this->usage = $usages;
        }
        $this->usage = $total;
    }

    public function set_parent_id()
    {
        $monthly = MonthlyUsage::where('meter_id', $this->meter_id)->orderBy('collected_date', 'desc')->first();
        if ($monthly) {
            $monthly_date = Carbon::createFromTimestamp($monthly->collected_date);
            if ($monthly_date->isSameMonth($this->c_time())) {
                $this->monthly_usage_id = $daily->id;
                #set the parent id and do not create a new parent
                return;
            }
        }
        #if there is no monthly yet create one
        #$monthly = MonthlyUsage::make_and_save($this->meter_id, $this->collected_date, $this->usage, $this->c_time()->month);
        #$this->monthly_usage_id = $monthly->id;
        $this->monthly_usage_id = 1;
    }

    public function save_or_not()
    {
        #here check if it is a duplicate
        $old = static::where('meter_id', $this->meter_id)->orderBy('collected_date', 'desc')->first();
        if ($old) {
            $old_date = Carbon::createFromTimestamp($old->collected_date);
            if ($old_date->isSameDay($this->c_time())) {
                #if they are duplicate warn us of duplicates
                $redundant = new RedundantUsage();
                $redundant->name = static::class;
                $redundant->usage_id = $old->id;
                $redundant->save();
                # do not continue execution
                return;
            }
        }
        #if it is not duplicate save it
        #$this->save();
    }
}

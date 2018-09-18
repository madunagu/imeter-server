<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Usage;
use App\MonthlyUsage;

class DailyUsage extends Usage
{

    public static $key = 'day';
    public static $checker_function = 'isSameMonth';
    public static $parent_id = 'monthly_usage_id';
    public static $parent_name = MonthlyUsage::class;

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
                if ($this->make_children) {
                    $hourly = HourlyUsage::make_and_save($this->meter_number, $this->collected_date, $usage, $hour);
                }
                $hour++;
            }
            $this->usage = $total;
        } else {
            #noticed the same
            if ($this->make_children) {
                $hourly = HourlyUsage::make_and_save($this->meter_number, $this->collected_date, $usages, $hour);
            }
            $this->usage = $usages;
        }


    }

    public function set_parent_id()
    {
        $monthly = MonthlyUsage::where('meter_number', $this->meter_number)->orderBy('collected_date', 'desc')->first();
        if ($monthly) {
            $monthly_date = Carbon::createFromTimestamp($monthly->collected_date);
            if ($monthly_date->isSameMonth()($this->c_time())) {
                $this->monthly_usage_id = $monthly->id;
                #set the parent id and do not create a new parent
                return;
            }
        }
        #if there is no monthly yet create one
        $monthly = MonthlyUsage::make_and_save($this->meter_number, $this->collected_date, $this->usage, $this->c_time()->month);
        $this->monthly_usage_id = $monthly->id;
    }

    public function save_or_not()
    {
        #here check if it is a duplicate
        $old = static::where('meter_number', $this->meter_number)->orderBy('collected_date', 'desc')->first();
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
        $this->save();
    }

    public function update_usage()
    {
        $hours = HourlyUsage::where('daily_usage_id', $this->id)->get();
        $total_usage = 0;
        $total_cost = 0;
        $total_tarrif = 0;
        foreach ($hours as $hour) {
            $total_cost += $hour->cost;
            $total_usage += $hour->usage;
            $total_tarrif+=$hour->tarrif;
        }
        $this->cost = $total_cost;
        $this->usage = $total_usage;
        $this->tarrif = round($total_tarrif/count($hours),3);
        $last_usage = $this->get_last_usage();
        $this->change = abs($this->usage - $last_usage);
        #delta is a boolean if the change is positive or negative
        $this->delta = ($this->usage - $last_usage) > 0;
        $this->save();
    }
}

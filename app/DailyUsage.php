<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Custom\SyncsWithFirestore;

use App\Usage;
use App\MonthlyUsage;

class DailyUsage extends Usage
{
    use SyncsWithFirestore;

    public static $key = 'day';
    public static $checker_function = 'isSameMonth';
    public static $parent_id = 'monthly_usage_id';
    public static $parent_name = MonthlyUsage::class;


    public function set_parent_id(Meter $meter)
    {
        $monthly = MonthlyUsage::where('meter_id', $this->meter_id)->orderBy('collected_date', 'desc')->first();
        if ($monthly) {
            $monthly_date = Carbon::createFromTimestamp($monthly->collected_date);
            if ($monthly_date->isSameMonth($this->c_time())) {
                $this->monthly_usage_id = $monthly->id;
                $monthly->update_usage();
                #set the parent id and do not create a new parent
                return;
            }
        }
        #if there is no monthly yet create one
        $monthly = MonthlyUsage::make_and_save($meter, $this->collected_date, $this->usage, $this->cost, $this->c_time()->month);
        $this->monthly_usage_id = $monthly->id;
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
        $this->save();
    }

    public function update_usage()
    {
        $hours = HourlyUsage::where('daily_usage_id', $this->id)->get();
        $this->calculate_params($hours);
    }
}

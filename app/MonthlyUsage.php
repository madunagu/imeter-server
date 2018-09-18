<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use App\Usage;
use App\YearlyUsage;
use App\DailyUsage;

class MonthlyUsage extends Usage
{

    public static $key = 'month';
    public static $checker_function = 'isSameDay';
    public static $parent_id = 'daily_usage_id';
    public static $parent_name = YearlyUsage::class;

    public function set_parent_id()
    {
        $yearly = YearlyUsage::where('meter_number', $this->meter_number)->orderBy('collected_date', 'desc')->first();
        if ($yearly) {
            $yearly_date = Carbon::createFromTimestamp($yearly->collected_date);
            if ($yearly_date->isSameYear($this->c_time())) {
                $this->yearly_usage_id = $yearly->id;
                #here update the parent since a new child has been added
                $yearly->update_usage();
                #set the parent id and do not create a new parent
                return;
            }
        }
        #if there is no monthly yet create one
        $yearly = YearlyUsage::make_and_save($this->meter_number, $this->collected_date, $this->usage, $this->c_time()->year);
        $this->yearly_usage_id = $yearly->id;
    }
    public function save_or_not()
    {
        #here check if it is a duplicate
        $old = static::where('meter_number', $this->meter_number)->orderBy('collected_date', 'desc')->first();
        if ($old) {
            $old_date = Carbon::createFromTimestamp($old->collected_date);
            if ($old_date->isSameMonth($this->c_time())) {
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
        $hours = DailyUsage::where('monthly_usage_id', $this->id)->get();
        $this->calculate_params($hours);
    }
}

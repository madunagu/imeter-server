<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Usage;
use App\MonthlyUsage;

class YearlyUsage extends Usage
{
    public static $key = 'year';
    //
    public function save_or_not()
    {
        #here check if it is a duplicate
        $old = static::where('meter_number', $this->meter_number)->orderBy('collected_date', 'desc')->first();
        if ($old) {
            $old_date = Carbon::createFromTimestamp($old->collected_date);
            if ($old_date->isSameYear($this->c_time())) {
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
        $hours = MonthlyUsage::where('yearly_usage_id', $this->id)->get();
        $this->calculate_params($hours);
    }
}

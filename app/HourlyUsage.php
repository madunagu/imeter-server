<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;


use Mpociot\Firebase\SyncsWithFirebase;

use App\Usage;
use App\DailyUsage;

class HourlyUsage extends Usage
{
    use SyncsWithFirebase;

    public static $key = 'hour';
    public static $checker_function = 'isSameDay';
    public static $parent_id = 'daily_usage_id';
    public static $parent_name = DailyUsage::class;

    protected $fillable = [
        'meter_number', 'usage', 'cost', 'tarrif', 'hour',
        'collected_date', 'delta', 'down', 'daily_usage_id'
    ];

    public function set_parent_id()
    {
        $daily = DailyUsage::where('meter_number', $this->meter_number)->orderBy('collected_date', 'desc')->first();
        if ($daily) {
            $daily_date = Carbon::createFromTimestamp($daily->collected_date);
            if ($daily_date->isSameDay($this->c_time())) {
                $this->daily_usage_id = $daily->id;
                $daily->update_usage();
                #set the parent id and do not create a new parent
                return;
            }
        }
        #if there is no monthly yet create one
        $daily = DailyUsage::make_and_save($this->meter_number, $this->collected_date, $this->usage, $this->c_time()->day);
        $this->daily_usage_id = $daily->id;
    }

    public function save_or_not()
    {
        #here check if it is a duplicate
        $old = static::where('meter_number', $this->meter_number)->orderBy('collected_date', 'desc')->first();
        if ($old) {
            $old_date = Carbon::createFromTimestamp($old->collected_date);
            if ($old_date->diffInHours($this->c_time())<=0 || ($old_date->isSameDay($this->c_time()) && $old->hour == $this->hour) ) {
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
}

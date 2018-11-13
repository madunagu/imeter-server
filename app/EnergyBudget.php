<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Usage;

class EnergyBudget extends Model
{
    public static $enforcementHourly = 'H';
    public static $enforcementDaily = 'D';
    public static $enforcementWeekly = 'W';
    public static $enforcementMonthly = 'M';
    public static $enforcementYearly = 'Y';

    public $enforcementHash = ['H','D','W','M','Y'];



    public function getParentDivisor()
    {
        $parentDivisor =  [
            'H'=>24,
            'D'=>getDaysInMonth(),
            'W'=>52,
            'M'=>12,
            'Y'=>1
        ];
        return $parentDivisor[$this->enforcement];
    }
    public function getDaysInMonth()
    {
        $dt = Carbon::now();
        return cal_days_in_month(CAL_GREGORIAN, $dt->month, $dt->year);
    }

    public function getEnergyUsage($timeKey) : Usage
    {
        switch ($timeKey) {
            case 'H':
            return HourlyUsage::where('meter_id', $this->$meter_id)->limit(24)->get();
            break;
            case 'D':
            return DailyUsage::where('meter_id', $this->meter_id)->limit(30)->get();
            break;
            case 'W':
            return WeeklyUsage::where('meter_id', $this->meter_id)->limit(5)->get();
            break;
            case 'M':
            return MonthlyUsage::where('meter_id', $this->meter_id)->limit(12)->get();
            break;
            case 'Y':
            return YearlyUsage::where('meter_id', $this->meter_id)->limit(30)->get();
            break;
            ;
        }
    }

    public function compareUsage($meter_id)
    {
        $budget = EnergyBudget::where('meter_id', $meter_id)->orderBy('id', 'desc')->first();
        if ($budget) {
            $parentUsage = $this->getEnergyUsage($this->returnUpperEquivalent());
            if ($budget->energy_budget < ($parentUsage/$this->getParentDivisor())) {
                # budget has overflown
                $averageUsage = ceil($parentUsage/$this->getParentDivisor());
                #warn user
                UserNotification::create('1', 'You Have Surpassed Your Energy Budget', 'your average energy usage is now $averageUsage which is more than $budget', '0', $meter_id);
            }
        }
    }

    public function returnUpperEquivalent()
    {
        $meKey = array_search($this->enforcement, $this->enforcementHash);
        return $this->enforcementHash[($meKey + 1)];
    }
}

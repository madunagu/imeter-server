<?php

namespace App;

use Carbon\Carbon;
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



    public function getParentDivisor(): float
    {
        $parentDivisor =  [
            'H'=>24,
            'D'=>$this->getDaysInMonth(),
            'W'=>4.34524,
            'M'=>12,
            'Y'=>1
        ];
        return $parentDivisor[$this->enforcement];
    }
    public function getDaysInMonth(): int
    {
        $dt = Carbon::now();
        return cal_days_in_month(CAL_GREGORIAN, $dt->month, $dt->year);
    }

    public function getEnergyUsage($timeKey)
    {
        switch ($timeKey) {
            case 'H':
            return HourlyUsage::where('meter_id', $this->meter_id)->orderBy('id', 'desc')->skip(1)->take(1)->get()->first();
            break;
            case 'D':
            return DailyUsage::where('meter_id', $this->meter_id)->orderBy('id', 'desc')->skip(1)->take(1)->get()->first();
            break;
            case 'W':
            return WeeklyUsage::where('meter_id', $this->meter_id)->orderBy('id', 'desc')->skip(1)->take(1)->get()->first();
            break;
            case 'M':
            return MonthlyUsage::where('meter_id', $this->meter_id)->orderBy('id', 'desc')->skip(1)->take(1)->get()->first();
            break;
            case 'Y':
            return YearlyUsage::where('meter_id', $this->meter_id)->orderBy('id', 'desc')->skip(1)->take(1)->get()->first();
            break;
        }
    }

    public static function compareUsage($meter_id)
    {
        $budget = EnergyBudget::where('meter_id', $meter_id)->orderBy('id', 'desc')->first();
        if ($budget) {
            $parentUsage = $budget->getEnergyUsage($budget->returnUpperEquivalent());
            if(!$parentUsage){
                return;
            }
            $parentCost = $parentUsage->cost;
            $parentDivisor = $budget->getParentDivisor();
            if ($budget->energy_budget < ($parentCost/$parentDivisor)) {
                # budget has overflown
                $averageUsage = ceil($parentCost/$parentDivisor);
                #warn user
                if($budget->should_shutdown){
                    Meter::where('meter_id',$meter_id)->first()->toggleOff();
                }
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

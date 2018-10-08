<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnergyBudget extends Model
{
    public static $enforcementHourly = 'H';
    public static $enforcementDaily = 'D';
    public static $enforcementWeekly = 'W';
    public static $enforcementMonthly = 'M';
    public static $enforcementYearly = 'Y';
}

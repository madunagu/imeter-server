<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServerRequest extends Model
{
    #protocol for most communication
    public static $requestTypeToggleConnection = '2';
    public static $requestKeyToggleConnection = 'CD';
    public static $requestTypePowerTime = '1';
    public static $requestKeyPowerTime = 'PT';
    public static $requestKeyEnergyBudget = 'EB';
    public static $requestTypeEnergyBudget = '3';
    public static $requestKeyIOT = '';
    public static $requestTypeIOT = '4';
}

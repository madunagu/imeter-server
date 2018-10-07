<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PowerToggle extends Model
{
    public static $meter_originated = '1';
    public static $moblie_app_originated = '2';
    public static $web_originated = '3';

    public static $connected = '1';
    public static $disconnected = '0';
}

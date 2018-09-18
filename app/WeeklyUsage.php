<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Mpociot\Firebase\SyncsWithFirebase;
use App\Usage;

class WeeklyUsage extends Usage
{
    use SyncsWithFirebase;

    public static $key = 'week';

    //
}

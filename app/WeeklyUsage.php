<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\Custom\SyncsWithFirestore;
use App\Usage;

class WeeklyUsage extends Usage
{
    use SyncsWithFirestore;

    public static $key = 'week';

    //
}

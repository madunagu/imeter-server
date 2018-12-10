<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TimeOfUse extends Model
{
    public static function getArray(Meter $meter): array
    {
        $touOrdered = [];
        $tous = TimeOfUse::where('meter_type_id', $meter->meter_type_id)->where('disco_id', $meter->disco_id)->limit(24)->orderBy('hour')->get();
        foreach ($tous as $tou) {
            $touOrdered[] = $tou->tarrif;
        }
        return $touOrdered;
    }
}

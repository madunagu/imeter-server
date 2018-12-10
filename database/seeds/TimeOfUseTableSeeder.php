<?php

use Illuminate\Database\Seeder;
use App\TimeOfUse;

class TimeOfUseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // TODO: add tou for default settings
        $count = 0;
        while ($count<24) {
            $tou = new TimeOfUse();
            $tou->meter_type_id = 1;
            $tou->disco_id = 1;
            $tou->tarrif = rand(2, 100);
            $tou->hour = $count;
            $tou->save();
            $count++;
        }
    }
}

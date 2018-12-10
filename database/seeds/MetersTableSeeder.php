<?php

use Illuminate\Database\Seeder;
use App\Meter;

class MetersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $alpha = new Meter();
        $alpha->number = '01223567AB';
        $alpha->user_id = 1;
        $alpha->balance = 5000;
        $alpha->disco_id = 1;
        $alpha->phone = '+2348065708630';
        $alpha->meter_type_id = 1;
        $alpha->save();

        //
    }
}

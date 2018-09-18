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
        $alpha->tarrif = 14;
        $alpha->balance = 5000;
        $alpha->address = 'somewhere';
        $alpha->phone = '+2348065708630';
        $alpha->type = 'meter_type';
        $alpha->save();

        //
    }
}

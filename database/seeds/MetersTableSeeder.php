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
        $alpha->meter_number = 'sldkkd233';
        
        //
    }
}

<?php

use Illuminate\Database\Seeder;

use App\EncryptionKey;
use App\Meter;

class EncryptionKeySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $encrytion = new EncryptionKey();
        $encrytion->meter_id = Meter::where('id',1)->first()->id;
        $encrytion->input_key = str_random(10);
        $encrytion->output_key = str_random(10);
        $encrytion->save();
    }
}

<?php

use Illuminate\Database\Seeder;
use App\Disco;

class DiscosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $disco = new Disco();
        $disco->name = 'Eko Electricity Distribution Company';
        $disco->short = 'EEDC';
        $disco->save();
        $disco = new Disco();
        $disco->name = 'Benin Electricity Distribution Company';
        $disco->short = 'BEDC';
        $disco->save();
    }
}

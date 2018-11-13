<?php

use Illuminate\Database\Seeder;
use App\MeterType;

class MeterTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $meterType  = new MeterType();
        $meterType->name = 'SINGLE_PHASE_WITH_SR';
        $meterType->group = '1P2W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'SINGLE_PHASE_WITH_CT';
        $meterType->group = '1P2W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'SINGLE_PHASE_WITH_RC';
        $meterType->group = '1P2W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'SINGLE_PHASE_WITH_SR_CT';
        $meterType->group = '1P2W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'SINGLE_PHASE_WITH_CT_CT';
        $meterType->group = '1P2W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'SINGLE_PHASE_WITH_RC_RC';
        $meterType->group = '1P2W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'SINGLE_PHASE_WITH_SR_CT';
        $meterType->group = '1P2W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'POLY_PHASE_WITH_SR_SR_SR';
        $meterType->group = '3P3W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'POLY_PHASE_WITH_CT_CT_CT';
        $meterType->group = '3P3W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'POLY_PHASE_WITH_RC_RC_RC';
        $meterType->group = '3P3W';
        $meterType->save();

        $meterType  = new MeterType();
        $meterType->name = 'POLY_PHASE_WITH_CT_CT_CT_CT';
        $meterType->group = '3P4W';
        $meterType->save();


        $meterType  = new MeterType();
        $meterType->name = 'POLY_PHASE_WITH_RC_RC_RC_RC';
        $meterType->group = '3P4W';
        $meterType->save();
    }
}

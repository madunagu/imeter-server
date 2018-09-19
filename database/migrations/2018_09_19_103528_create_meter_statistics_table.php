<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meter_number');
            $table->decimal('average_temprature',15,8);
            $table->decimal('average_voltage',50,12);
            $table->decimal('average_current',50,12);
            $table->decimal('average_frequency',50,12);
            $table->decimal('battery_level',50,12);
            $table->bigInteger('collected_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meter_statistics');
    }
}

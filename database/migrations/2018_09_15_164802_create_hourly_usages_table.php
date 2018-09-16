<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHourlyUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hourly_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->string('usage');
            $table->string('cost');
            $table->string('collected_date');
            $table->string('change');
            $table->boolean('delta');
            $table->integer('daily_usage_id');
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
        Schema::dropIfExists('hourly_usages');
    }
}

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
            $table->string('meter_id');
            $table->decimal('usage',50,12);
            $table->decimal('cost',50,12);
            $table->decimal('tarrif',5,3);
            $table->integer('hour');
            $table->bigInteger('collected_date');
            $table->decimal('delta',50,12);
            $table->boolean('down');
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

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
            $table->increments('id')->nullable();
            $table->string('meter_id');
            $table->decimal('usage',50,12);
            $table->decimal('cost',50,12);
            $table->timestamp('collected_date');
            $table->decimal('change',50,12);
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

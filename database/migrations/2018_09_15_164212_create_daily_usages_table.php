<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDailyUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->decimal('usage',20,8);
            $table->decimal('cost',20,8);
            $table->bigInteger('collected_date');
            $table->integer('day');
            $table->decimal('delta',50,12);
            $table->integer('monthly_usage_id');
            $table->boolean('down');
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
        Schema::dropIfExists('daily_usages');
    }
}

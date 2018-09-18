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
            $table->string('meter_number');
            $table->decimal('usage',20,8);
            $table->decimal('cost',20,8);
            $table->decimal('tarrif',5,3);
            $table->timestamp('collected_date');
            $table->integer('day');
            $table->decimal('change',50,12);
            $table->integer('monthly_usage_id');
            $table->boolean('delta');
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

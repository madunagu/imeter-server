<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeterRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meter_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('meter_number');
            $table->string('message_type');
            $table->text('body');
            $table->bigInteger('sent_time');
            $table->bigInteger('recieved_time');
            $table->bigInteger('time_lag');
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
        Schema::dropIfExists('meter_requests');
    }
}

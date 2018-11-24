<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServerRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('server_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->string('request_type');
            $table->string('request_key');
            $table->string('request_value');
            $table->bigInteger('done_time')->nullable(true);
        //    $table->integer('relative_id');
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
        Schema::dropIfExists('server_requests');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateYearlyUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('yearly_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->string('usage');
            $table->string('cost');
            $table->string('year');
            $table->string('change');
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
        Schema::dropIfExists('yearly_usages');
    }
}

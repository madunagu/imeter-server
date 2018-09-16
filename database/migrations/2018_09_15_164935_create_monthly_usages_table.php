<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlyUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->string('usage');
            $table->string('cost');
            $table->string('month');
            $table->string('change');
            $table->string('collected_date');
            $table->integer('yearly_usage_id');
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
        Schema::dropIfExists('monthly_usages');
    }
}

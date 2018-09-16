<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('weekly_usages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('meter_id');
            $table->string('usage');
            $table->string('cost');
            $table->timestamp('collected_date');
            $table->string('week');
            $table->string('change');
            $table->string('yearly_usage_id');
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
        Schema::dropIfExists('weekly_usages');
    }
}

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
            $table->decimal('usage',50,12);
            $table->decimal('cost',50,12);
            $table->bigInteger('collected_date');
            $table->string('week');
            $table->decimal('delta',50,12);
            $table->string('yearly_usage_id');
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
        Schema::dropIfExists('weekly_usages');
    }
}

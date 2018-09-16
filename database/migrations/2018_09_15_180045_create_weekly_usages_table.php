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
            $table->string('meter_id')->nullable();
            $table->decimal('usage',50,12);
            $table->decimal('cost',50,12);
            $table->timestamp('collected_date');
            $table->string('week');
            $table->decimal('change',50,12);
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

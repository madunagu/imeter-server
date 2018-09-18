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
            $table->string('meter_number');
            $table->decimal('usage',50,12);
            $table->decimal('cost',50,12);
            $table->decimal('tarrif',5,3);
            $table->string('month');
            $table->decimal('change',50,12);
            $table->timestamp('collected_date');
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

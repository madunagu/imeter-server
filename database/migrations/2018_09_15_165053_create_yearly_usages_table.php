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
            $table->string('meter_number');
            $table->decimal('usage',50,12);
            $table->decimal('cost',50,12);
            $table->decimal('tarrif',5,3);
            $table->timestamp('collected_date');
            $table->string('year');
            $table->decimal('change',50,12);
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

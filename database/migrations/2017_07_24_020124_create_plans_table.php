<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_id')->unsigned();
            $table->float('start_lat', 10, 6);
            $table->float('start_lng', 10, 6);
            $table->string('start_name');
            $table->float('end_lat', 10, 6);
            $table->float('end_lng', 10, 6);
            $table->string('end_name');
            $table->datetime('time_start');
            $table->datetime('time_end');
            $table->string('vehicle');
            $table->string('activities');
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
        Schema::dropIfExists('plans');
    }
}

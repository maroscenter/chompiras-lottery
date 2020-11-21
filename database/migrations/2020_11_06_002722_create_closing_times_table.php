<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClosingTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('closing_times', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->string('day');
            $table->time('time');

            $table->unsignedInteger('lottery_id');
            $table->foreign('lottery_id')->references('id')->on('lotteries');
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
        Schema::dropIfExists('closing_times');
    }
}

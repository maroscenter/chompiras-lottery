<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketPlaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_plays', function (Blueprint $table) {
            $table->increments('id');

            $table->string('number');
            $table->unsignedInteger('points');
            $table->string('type');

            $table->unsignedInteger('ticket_id');
            $table->foreign('ticket_id')->references('id')->on('tickets');

//            $table->unsignedInteger('lottery_id');
//            $table->foreign('lottery_id')->references('id')->on('lotteries');

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
        Schema::dropIfExists('ticket_plays');
    }
}

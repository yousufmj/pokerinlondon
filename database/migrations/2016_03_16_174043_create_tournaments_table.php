<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTournamentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('casino_id')->unsigned();
            $table->foreign('casino_id')->references('id')->on('casino')->onDelete('cascade');
            $table->string('casino');
            $table->dateTime('date');
            $table->string('buyin');
            $table->string('event');
            $table->string('start'); 
            $table->string('stack');
            $table->string('clock');
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
        Schema::drop('tournaments');
    }
}
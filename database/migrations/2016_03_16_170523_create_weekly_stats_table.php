<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeeklyStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
     public function up()
    {
        Schema::create('weekly_stats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('casino_id')->unsigned();
            $table->foreign('casino_id')->references('id')->on('casino')->onDelete('cascade');
            $table->string('stakes');
            $table->string('tables');
            $table->string('game');
            $table->string('weekday'); 
            $table->dateTime('update_time');
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
        Schema::drop('weekly_stats');
    }
}

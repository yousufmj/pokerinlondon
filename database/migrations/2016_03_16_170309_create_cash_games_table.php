<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCashGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashGames', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('casino_id')->unsigned();
            $table->foreign('casino_id')->references('id')->on('casino')->onDelete('cascade');
            $table->string('stakes');
            $table->string('tables');
            $table->string('game'); 
            $table->integer('update');
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
        Schema::drop('cashGames');
    }
}

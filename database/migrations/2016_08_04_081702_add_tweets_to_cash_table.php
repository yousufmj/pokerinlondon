<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTweetsToCashTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('cashGames', function($table) {
             $table->datetime('tweet_time');
             $table->string('url');
         });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cashGames', function($table) {
             $table->dropColumn('tweet_time');
             $table->dropColumn('url');
         });
    }
}

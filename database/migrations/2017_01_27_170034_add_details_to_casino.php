<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetailsToCasino extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('casino', function($table) {
             $table->string('address');
             $table->string('postcode');
             $table->string('info');
             $table->string('logo');
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
        Schema::table('casino', function($table) {
             $table->dropColumn('address');
             $table->dropColumn('postcode');
             $table->dropColumn('info');
             $table->dropColumn('logo');
             $table->dropColumn('url');
         });
    }
}

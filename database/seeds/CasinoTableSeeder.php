<?php

use Illuminate\Database\Seeder;

class CasinoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	DB::table('casino')->insert([
    		['name' => 'Aspers'],
    		['name' => 'Empire'],
    		['name' => 'Hippodrome'],
    		['name' => 'Golden Horsehoe'],
    		['name' => 'The Vic']
		]);

   
    }
}

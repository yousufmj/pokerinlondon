<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Cash;
use App\Weekly;
use View;
use DB;
use App\Http\Requests;
use Carbon\Carbon;

class DailyCash extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:cash';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add cash games into weekly table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        for ($i=0; $i <= 23 ; $i++) { 
        
            $today = Carbon::parse('1 days ago')->hour($i)->minute(00)->format('Y-m-d H:i');
            $cashGames = Cash::where('created_at','LIKE',"$today%");
            $results = $cashGames->get();

            foreach ($results as $r) {
                print $r->game;

                Weekly::create(array(
                    'casino_id' => $r->casino_id,
                    'tables' => $r->tables,
                    'game' => $r->game,
                    'weekday' => Carbon::parse('1 days ago')->format('l'),
                    'stakes' => $r->stakes,
                    'update_time' => $r->created_at

                ));

            }

            $cashGames->delete();
        }

         

    }
}

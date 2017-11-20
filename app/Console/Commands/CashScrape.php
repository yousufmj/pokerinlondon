<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Cash;
use App\Tournaments;
use View;
use DB;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;
use Carbon\Carbon;

class CashScrape extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cash:scrape';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scrape for cash games';

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
        Cash::where('update', '=', 1)->update(['update' => 0]);

        // tart scrape
        $html = HtmlDomParser::file_get_html( 'http://pokerstatus.co.uk/london/' );

        // find table row
        $row = $html->find('table tr');

        // looping through all tags
        foreach ($row as $r) {

            // strip html tags and white space
            $casino = trim(strip_tags($r->children(0)));
            $info = trim(strip_tags($r->children(1)));
            $running = trim(strip_tags($r->children(2)));

            $time = substr($running, -1);

            if ($time == 'm') {
                $running = '1h';
            }

            $stripRun = substr($running, 0, -1);


            if ($casino != 'Venue') {

                // Split the information column to get info seperated 
                $explode = explode(' ', $info);
                $tables = $explode[0];
                $stakes = $explode[2];
                $game = $explode[3];

                if ($casino == 'Aspers') {
                        $casino = 1;
                }
                if ($casino == 'Empire') {
                        $casino = 2;
                }
                if ($casino == 'Hippodrome') {
                        $casino = 3;
                }
                if ($casino == 'Golden Horseshoe') {
                        $casino = 4;
                }
                if ($casino == 'GrosvenorVictoria') {
                        $casino = 5;
                }

                // Add fields into db
                Cash::create(array(
                        'casino_id' => $casino,
                        'tables' => $tables,
                        'game' => $game,
                        'stakes' => $stakes,
                        'update' => 1

                ));
               
            }

        }
    }
}

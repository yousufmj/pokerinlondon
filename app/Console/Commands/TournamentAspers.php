<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tournaments;
use View;
use DB;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;
use Carbon\Carbon;

class TournamentAspers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournament:aspers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scrape aspers tournament';

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

        $html = HtmlDomParser::file_get_html( 'http://www.aspersstratford.co.uk/poker-tournaments.html' );

        $row = $html->find('table#table-zebra tbody tr');


        foreach ($row as $r) {

            // check if theres a day in the first row
            $ch = trim(strip_tags($r->children(0)));

            if ($ch != "Day") {
                if (strlen($ch) <= 3 ) {


                    
                    $date = trim(strip_tags($r->children(0)));
                    $date = \DateTime::createFromFormat('D', $date)->format('d-m-Y');
                    $date = Carbon::parse($date);
                    $start = trim(strip_tags($r->children(2)));
                    $event = trim(strip_tags($r->children(1)));
                    $buy = trim(strip_tags($r->children(3)));
                    $stack = trim(strip_tags($r->children(6)));
                    $clock = $this->aspersClock($event);

                }else{
                    // declare rows
                    $start = strip_tags($r->children(1));
                    $event = strip_tags($r->children(0));
                    $buy = strip_tags($r->children(2));
                    $stack = strip_tags($r->children(5));
                    $date = Tournaments::all()->last()->date;
                    $clock = $this->aspersClock($event);
                }
    
                $game = array(
                            'casino_id' => 1,
                            'casino' => 'Aspers',
                            'date' => $date,
                            'buyin' => $buy,
                            'event' => trim($event),
                            'clock' => $clock,
                            'stack' => $stack,
                            'start' => $start,
                            );

                // Check if theres already that field
                $check = Tournaments::where('casino','=','Aspers')
                                            ->where('event', '=', $event)
                                            ->where('date', '=', $date)->count();

                if ($check) {
                    echo $event . "Event has already been inserted <br>";
                }else{
                    
                    Tournaments::create($game);
                    echo $event . "Successfully added <br>";
                }


            }
        }

    }

    /***********************************
    add the month to the end of the date
    ***********************************/
    private function addMonth($date)
    {

        $tdate = substr($date, 4, -2);
        $today = date('j');
        $add_month = strtotime( "+1 month", strtotime( date("y-m-j") ) );
        $month = date("M",$add_month);
        $date = substr($date, 0, -2);


        if ($tdate >= $today) {
            $r = $date . ' ' . date('M');

            return Carbon::parse($r);
        }else{
            $r = $date . ' ' . $month;

            return Carbon::parse($r);
        }
    }


    /***********************************
    check if its this month
    ***********************************/
    private function thisMonth($date)
    {
        $tdate = substr($date, 4, -2);
        $today = date('j');

        if ($tdate >= $today) {
            return true;
        }


    }

    /***********************************
    Get clock times
    ***********************************/
    private function aspersClock($event)
    {
        $daily = '6×20/25';
        $sat = '3x30/15';
        $super = '4x30/20';

        if ($event == 'Afternoon Annihilator' || $event == 'Multi Madness' || $event == 'Big Bounty ' || $event == 'Aspers Triple Chance') 
        {
            return $daily;

        }elseif ($event == 'Satellite' || $event == 'Stamp Card Satellite') 
        {
            return $sat;

        }elseif ($event == 'Friday Fifty Five' || $event == 'Super 60') 
        {
            return $super;

        }else
        {
            return '';
        }
    }   

}
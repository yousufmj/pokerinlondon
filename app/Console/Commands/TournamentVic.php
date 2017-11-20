<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tournaments;
use View;
use DB;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;
use Carbon\Carbon;

class TournamentVic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tournament:vic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'scrape vic tournies';

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
        $html = HtmlDomParser::file_get_html( 'http://www.thepokerroom.co.uk/poker-room-2/tournaments/' );

            $row = $html->find('table.tournament-table tr');

            foreach ($row as $r) {
                
    
                $date = strip_tags($r->children(0));
                $event = strip_tags($r->children(2));
                $start = strip_tags($r->children(5));

                //make sure only this months dates are shown
                if (strlen($date) > 3 && strlen($date) < 9 && $this->thisMonth($date)) {

                    $game = array(
                        'casino' => 'The Vic',
                        'casino_id' => 5,
                        'date' => $this->addMonth($date),
                        'buyin' => strip_tags($r->children(1)),
                        'event' => $event,
                        'clock' => strip_tags($r->children(3)),
                        'stack' => strip_tags($r->children(4)),
                        'start' => date("H:i", strtotime($start))
                        );

                    $check = Tournaments::where('casino','=','The Vic')
                                                ->where('event', '=', $event)
                                                ->where('date', '=', $this->addMonth($date))->count();

                    if ($check) {
                        echo $event . 'Event has already been inserted <br>';
                    }else{
                        
                        Tournaments::create($game);
                        echo $event . 'Successfully added <br>';
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
}

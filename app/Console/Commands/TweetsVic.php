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
use Thujohn\Twitter\Facades\Twitter;

class TweetsVic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:vic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $theVicTweet = Twitter::getUserTimeline(['screen_name' => 'ThePokerRoomUK', 'count' => 10, 'format' => 'array', 'exclude_replies' => 'true', 'include_rts' => 'false']);
        $theVic = $theVicTweet[0]{'text'};


        $tweetdate = new Carbon($theVicTweet[0]{'created_at'});
        $tweeturl = $theVicTweet[0]{'id'};

        $theVic = $this->checkTweet($theVic);
       
        $uniqueTweet = $this->uniqueTweet($tweeturl);
        
        if (empty($uniqueTweet)) {
            if ($theVic) {
                
                $reg = '/[PLO||NLH]+\s([\d-\(\)\s]+)/';
                preg_match_all($reg ,$theVic, $matches);
                $this->updateCash($matches[0],$tweetdate,$tweeturl);

                print "updated \n";
            }else{
                print "not updated \n ";
                
            }
        }else{
            echo "This tweet is already in the database";
        }


    }

    private function uniqueTweet($id)
    {
        $check = DB::table('cashGames')
                    ->where('update', '=',1)
                    ->where('url', 'like',"%$id")
                    ->get();

        if ($check) {
            return true;
        }else{
            return false;
        }
    }

    private function checkTweet($tweet)
    {
        if(stripos($tweet, 'cash') === false ){
            return false;
        }

        $regex = "((\#\w+)|(https?:\/\/.*))";
        $tweet = preg_replace($regex, ' ', $tweet);
        $tweet = str_replace('.', ' ', $tweet);
       

        return $tweet;
    }

    private function updateCash($games,$date,$url)
    {   
        
        $nlhGames = str_replace(' ', '', $games);
        $nlhGames = preg_split("/[\s,]+/",rtrim($nlhGames[0]));

        $count = count($nlhGames);

        $nlh = trim($nlhGames[0]);
        if ( strtolower($nlh) != 'nlh') {
            print strtolower($nlh);
            return false;

        }else{
           
            Cash::where('update', '=', 1)->where('casino_id', '=', 5)->update(['update' => 0]);
            if(strtolower($nlh) == 'update') 'NLH';

            for ($i=1; $i < $count; $i++) { 

                $nlhs = explode("(" , rtrim($nlhGames[$i], ")"));
                $stakes = str_replace('-', '/', $nlhs[0]);
                
                 Cash::create(array(
                        'casino' => 'The Vic',
                        'casino_id' => 5,
                        'tables' => $nlhs[1],
                        'game' => $nlh,
                        'stakes' => $stakes,
                        'tweet_time' => $date,
                        'url' => "https://twitter.com/statuses/$url",
                        'update' => 1

                ));

            }

            if (empty($games[1])) {
                return false;
            }

            
        }

        $ploGames = preg_split("/[\s,]+/",rtrim($games[1]));

        $countp = count($ploGames);

        $plo = trim($ploGames[0]);
        print_r($plo);

        if ( $plo == 'PLO') {


            for ($i=1; $i < $countp; $i++) { 

                $plos = explode("(" , rtrim($ploGames[$i], ")"));
                $stakes = str_replace('-', '/', $plos[0]);
                 Cash::create(array(
                        'casino' => 'The Vic',
                        'casino_id' => 5,
                        'tables' => $plos[1],
                        'game' => $plo,
                        'stakes' => $stakes,
                        'tweet_time' => $date,
                        'url' => "https://twitter.com/statuses/$url",
                        'update' => 1

                ));

            }
        }       

        return;

    }
}

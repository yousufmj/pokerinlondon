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


class EmpireTweets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'empire:tweets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Grab empire tweets';

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
        // Get The first tweet from empire
        $empiresTweet = Twitter::getUserTimeline(['screen_name' => 'EmpirePokerRoom', 'count' => 20, 'format' => 'array']);
        $empire = $empiresTweet[0]{'text'};

        $tweetdate = new Carbon($empiresTweet[0]{'created_at'});
        $tweeturl = $empiresTweet[0]{'id'};

        //remove any unwanted characters
        $empire = preg_replace('/[^A-Za-z0-9\-\/\s]/', '',$empire);

        //Check if tweet is already stored
        $uniqueTweet = $this->uniqueTweet($tweeturl);

        $sanitizeTweet = $this->sanitizeTweet($empire);

        //Return all the current games
        
        if ($uniqueTweet) {

            if ($sanitizeTweet) {

                $games = $this->tweetFormat($sanitizeTweet);
                $updated = $this->updateCash($games,'Empire',2,$tweetdate,$tweeturl);

                print_r($games);
                echo "<br>";
                echo  "Tweet: " . $sanitizeTweet . "<br>";
                echo  $updated . "<br>";

            }else{

                echo "This is not a cash game update";
            }

        }else{

            echo "This tweet is already in the database";
        }
        
        

    }

    //clean up tweet by removing any links or hastags at the end
    
    private function uniqueTweet($id)
    {
        $check = DB::table('cashGames')
                    ->where('update', '=',1)
                    ->where('url', '=',$id)
                    ->get();
        if ($check) {
            return true;
        }else{
            return false;
        }
    }

    // If a cash game tweet clean unwanted text
    private function sanitizeTweet($tweet)
    {
        //echo $tweet;
        if(stripos($tweet, 'games') !== false || stripos($tweet, 'nlh') !== false){
            
            $regex = '/((?:(?:\#\w+|https?:\/\/.*)\s?)+)$/';
            $tweet = preg_replace($regex, ' ', $tweet);
            $tweet = str_replace('.', ' ', $tweet);

            return $tweet;
            
        }

        return false;

        
    }

    private function tweetFormat($tweet)
    {

        $regex = '/(\d+\s\w?\s?\d\/\s?\W?\d\s?[a-zA-Z]*)/';
        $regex = preg_match_all($regex,$tweet,$matches);


        if ($regex) {

            if (stripos($tweet, 'x') !== false) {
                $games = preg_replace('/\sx/', ' ', $matches[0]);

            }else{
                $games = $matches[0];
            }

            $games = preg_replace('/games/', 'NLH', $games);

            return $games;
            
            
            
        }else{

            return "regex not met!!!!!! <br> <br>" . "\n";
        }



    }


    private function updateCash($games,$casino,$id,$date,$url)
    {
         Cash::where('update', '=', 1)->where('casino_id','=',$id)->update(['update' => 0]);


            foreach ($games as $game) { 

                $game = explode(' ', $game);

                Cash::create(array(
                        'casino' => $casino,
                        'casino_id' => $id,
                        'tables' => $game[0],
                        'game' => $game[2],
                        'stakes' => $game[1],
                        'tweet_time' => $date,
                        'url' => "https://twitter.com/statuses/$url",
                        'update' => 1

                ));

            }
            


        return "Tweets Successfully updated heres the Url: https://twitter.com/statuses/$url";

    }
}   

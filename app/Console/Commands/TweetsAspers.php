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

class TweetsAspers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tweets:aspers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get cash game updates for aspers';

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
                // Get The first tweet from aspers
       $aspersTweet = Twitter::getUserTimeline(['screen_name' => 'AspersPoker', 'count' => 10, 'format' => 'array']);
        $aspers = $aspersTweet[0]{'text'};

        $tweetdate = new Carbon($aspersTweet[0]{'created_at'});
        $tweeturl = $aspersTweet[0]{'id'};

        //remove any unwanted characters
        $aspers = preg_replace('/[^A-Za-z0-9\-\/\s]\(\)/', '',$aspers);

        $uniqueTweet = $this->uniqueTweet($tweeturl);

        $sanitizeTweet = $this->sanitizeTweet($aspers);
        
        if (empty($uniqueTweet)) {

            if ($sanitizeTweet) {

                $games = $this->tweetFormat($sanitizeTweet);
                $updated = $this->updateCash($games[0],$games[1],'Aspers',1,$tweetdate,$tweeturl);

                echo "Game tweet :";
                print_r($games);
                echo "<br>";
                echo  "Tweet: " . $sanitizeTweet . "<br>";
                echo  $updated . "<br>";

            }else{

                echo "This is not a cash game update";
            }

        }else{

            echo "This tweet is already in the database $tweeturl";
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

    //clean up tweet by removing any links or hastags at the end
    private function sanitizeTweet($tweet)
    {

        if(stripos($tweet, 'cash') === false){
            return "Not a cash game tweet!!!!! <br>";
        }

        $regex = '/((?:(?:\#\w+|https?:\/\/.*)\s?)+)$/';
        $tweet = preg_replace($regex, ' ', $tweet);
        $tweet = str_replace('.', ' ', $tweet);

        return $tweet;
    }

    private function tweetFormat($tweet)
    {
        //  £1/£1 x 4
        $regex1 = '/(\d\/\s?£?\d+\s?\w\s?\d+)/u'; 
        $regex1 = preg_match_all($regex1 ,$tweet);
        //  £1/2 (4)
        $regex2 = '/(\d\/£?\d+\s?\(\d+\))/u';
        $regex2 = preg_match_all($regex2 ,$tweet);
        //  1x £1/1
        $regex3 = '/(\d\s?\w\s?£?\d\/£?\d+)/u';
        $regex3 = preg_match_all($regex3 ,$tweet);

        $aspersNLH = '';
        $aspersPLO = '';
        $NLH = '';
        $PLO = '';

        if ($regex1) {

            $split = preg_split("/\#/", $tweet);

            
            if(stripos($split[1], 'nlh') !== false){

                
                if (isset($split[2])) {
                    preg_match_all("/(\d\/\s?£?\d*\s?\w\s?\d+)/u", $split[2],$aspersPLO);
                }

                preg_match_all("/(\d\/\s?£?\d*\s?\w\s?\d+)/u", $split[1],$aspersNLH);

            //if the the first part of an array is PLO regex the string and place it into an array  
            }else{

                if (isset($split[2])) {
                    preg_match_all("/(\d\/\s?£?\d*\s?\w\s?\d+)/u", $split[2],$aspersNLH);
                }

                preg_match_all("/(\d\/\s?£?\d*\s?\w\s?\d+)/u", $split[1],$aspersPLO);
                    
            }

            $NLHS = str_replace('(', 'x ',$aspersNLH[0]);
            $NLH = str_replace(')', '',$NLHS);
            $PLOS = str_replace('(', 'x ',$aspersPLO[0]);
            $PLO = str_replace(')', '',$PLOS);

            // Update the databse
            //$this->updateCash($NLH,$PLO,'Aspers',1,'');
            $game = array($NLH,$PLO);

            return $game;


        }elseif ($regex2) {

            $split = preg_split("/\#/", $tweet);
            

            //if the the first part of an array is NLH regex the string and place it into an array
            if(stripos($split[1], 'nlh') !== false){

                
                if (isset($split[2])) {
                    preg_match_all('/(\d\/£?\d+\s?\(\d+\))/u', $split[2],$aspersPLO);
                }

                preg_match_all('/(\d\/£?\d+\s?\(\d+\))/u', $split[1],$aspersNLH);

            //if the the first part of an array is PLO regex the string and place it into an array  
            }else{

                if (isset($split[2])) {
                    preg_match_all("/(\d\/£?\d+\s?\(\d+\))/u", $split[2],$aspersNLH);
                }

                preg_match_all("/(\d\/£?\d+\s?\(\d+\))/u", $split[1],$aspersPLO);
                    
            }

            $NLHS = str_replace('(', 'x ',$aspersNLH[0]);
            $NLH = str_replace(')', '',$NLHS);
            if($aspersPLO){
                $PLOS = str_replace('(', 'x ',$aspersPLO[0]);
                $PLO = str_replace(')', '',$PLOS);
            }   

            // Update the databse
            $game = array($NLH,$PLO);
            
            return $game;

        }elseif ($regex3) {
            if (strpos($tweet, '#NLH') !== false) {
                
            }else{
                $tweet = str_replace('NLH', '#NLH',$tweet);
            }
            if (strpos($tweet, '#PLO') !== false) {
                
            }else{
                $tweet = str_replace('PLO', '#PLO',$tweet);
            }
            $split = preg_split("/\#/", $tweet);

            if(stripos($split[1], 'nlh') !== false){
                
                if (isset($split[2])) {
                    preg_match_all('/(\d\s?\w\s?£?\d\/£?\d+)/u', $split[2],$aspersPLO);
                }

                preg_match_all('/(\d\s?\w\s?£?\d\/£?\d+)/u', $split[1],$aspersNLH);

            //if the the first part of an array is PLO regex the string and place it into an array  
            }else{

                if (isset($split[2])) {
                    preg_match_all('/(\d\s?\w\s?£?\d\/£?\d+)/u', $split[2],$aspersNLH);
                }

                preg_match_all('/(\d\s?\w\s?£?\d\/£?\d+)/u', $split[1],$aspersPLO);
                    
            }

            $final_plo = array();
            $final_nlh = array();

            // Go through each array and reformat the output
            if($aspersNLH){

                $raw_nlh = $aspersNLH[0];
                
                foreach($raw_nlh as $a){
                    $NLHS = explode('x', $a);
                    $NLH = $NLHS[1] .' x ' . $NLHS[0];
                    array_push($final_nlh,$NLH);
                }
            }
            
            if($aspersPLO){

                $raw_plo = $aspersPLO[0];
                
                foreach($raw_plo as $p){
                    $PLOS = explode('x', $p);
                    $PLO = $PLOS[1] .' x ' . $PLOS[0];
                    array_push($final_plo,$PLO);
                }
            }
            
            
           $game = array($final_nlh,$final_plo);

            return $game;

            
        }else{

            print "regex not met!!!!!! " . "\n";
        }

    }


    private function updateCash($nlh,$plo,$casino,$id,$date,$url)
    {
        if($nlh or $plo){
         Cash::where('update', '=', 1)->where('casino_id','=',$id)->update(['update' => 0]);
        }

        if ( $nlh ) {

       
            foreach ($nlh as $n) { 

                $nlhs = explode("x" ,$n);
                
                $stakes =  preg_replace('/[^A-Za-z0-9\-\/]/', '',$nlhs[0]);

                Cash::create(array(
                        'casino' => $casino,
                        'casino_id' => $id,
                        'tables' => $nlhs[1],
                        'game' => 'NLH',
                        'stakes' => $stakes,
                        'tweet_time' => $date,
                        'url' => "https://twitter.com/statuses/$url",
                        'update' => 1

                ));

            }

            
        }

        if ( $plo ) {

            foreach ($plo as $p) { 

                $plos = explode("x" ,$p);

                $stakes =  preg_replace('/[^A-Za-z0-9\-\/]/', '',$plos[0]);

                Cash::create(array(
                        'casino' => $casino,
                        'casino_id' => $id,
                        'tables' => $plos[1],
                        'game' => 'PLO',
                        'stakes' => $stakes,
                        'tweet_time' => $date,
                        'url' => "https://twitter.com/statuses/$url",
                        'update' => 1

                ));

            }

            
        }

        return true;

    }
}   
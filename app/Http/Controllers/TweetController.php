<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tournaments;
use App\Cash;
use View;
use DB;
use App\Http\Requests;
use Carbon\Carbon;
use Thujohn\Twitter\Facades\Twitter;


class TweetController extends Controller
{

	/*===========================================
	=            Todays tournaments             =
	===========================================*/

	public function getIndex()
	{
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
                print_r($sanitizeTweet);
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
        $regex1 = '/(\W\d\/\s?\W?\d*\s?\w\s?\d)/'; 
        $regex1 = preg_match_all($regex1 ,$tweet);
        //  £1/2 (4)
        $regex2 = '/(\W?\d\/\d+\s?\W\d\W)/';
        $regex2 = preg_match_all($regex2 ,$tweet);
        //  1x £1/1
        $regex3 = '/(\d\s?\w\s?\W?\d\W\d+)/';
        $regex3 = preg_match_all($regex3 ,$tweet);

        $aspersNLH = '';
        $aspersPLO = '';
        $NLH = '';
        $PLO = '';

        print_r($regex1[0]);

        if ($regex1) {

            $split = preg_split("/\#/", $tweet);


            if(stripos($split[1], 'nlh') !== false){

                
                if (isset($split[2])) {
                    preg_match_all("/(\d\/\s?\W?\d*\s?\w\s?\d)/", $split[2],$aspersPLO);
                }

                preg_match_all("/(\d\/\s?\W?\d*\s?\w\s?\d)/", $split[1],$aspersNLH);

            //if the the first part of an array is PLO regex the string and place it into an array  
            }else{

                if (isset($split[2])) {
                    preg_match_all("/(\d\/\s?\W?\d*\s?\w\s?\d)/", $split[2],$aspersNLH);
                }

                preg_match_all("/(\d\/\s?\W?\d*\s?\w\s?\d)/", $split[1],$aspersPLO);
                    
            }

            $NLHS = str_replace('(', 'x ',$aspersNLH[0]);
            $NLH = str_replace(')', '',$NLHS);
            if ($aspersPLO){
                $PLOS = str_replace('(', 'x ',$aspersPLO[0]);
                $PLO = str_replace(')', '',$PLOS);
            }

            $game = array($NLH,$PLO);

            return $game;


        }elseif ($regex2) {

            $split = preg_split("/\#/", $tweet);
            

            //if the the first part of an array is NLH regex the string and place it into an array
            if(stripos($split[1], 'nlh') !== false){

                
                if (isset($split[2])) {
                    preg_match_all("/(\W?\d\/\d+\s?\W\d\W)/", $split[2],$aspersPLO);
                }

                preg_match_all("/(\W?\d\/\d+\s?\W\d\W)/", $split[1],$aspersNLH);

            //if the the first part of an array is PLO regex the string and place it into an array  
            }else{

                if (isset($split[2])) {
                    preg_match_all("/(\W?\d\/\d+\s?\W\d\W)/", $split[2],$aspersNLH);
                }

                preg_match_all("/(\W?\d\/\d+\s?\W\d\W)/", $split[1],$aspersPLO);
                    
            }

            $NLHS = str_replace('(', 'x ',$aspersNLH[0]);
            $NLH = str_replace(')', '',$NLHS);
            if($aspersPLO){
                $PLOS = str_replace('(', 'x ',$aspersPLO[0]);
                $PLO = str_replace(')', '',$PLOS);
            }   

            
            $game = array($NLH,$PLO);
            return $game;

        }elseif ($regex3) {

            $split = preg_split("/\#/", $tweet);


            if(stripos($split[1], 'nlh') !== false){
                
                if (isset($split[2])) {
                    preg_match_all("/(\d\s?\w\s?\W?\d\W\d+)/", $split[2],$aspersPLO);
                }

                preg_match_all("/(\d\s?\w\s?\W?\d\W\d+)/", $split[1],$aspersNLH);

            //if the the first part of an array is PLO regex the string and place it into an array  
            }else{

                if (isset($split[2])) {
                    preg_match_all("/(\d\s?\w\s?\W?\d\W\d+)/", $split[2],$aspersNLH);
                }

                preg_match_all("/(\d\s?\w\s?\W?\d\W\d+)/", $split[1],$aspersPLO);
                    
            }

            $NLHS = explode('x', $aspersNLH[0]);
            $NLH = $NLHS[1] .' x' . $NLHS[0];
            if($aspersPLO){
                $PLOS = explode('x', $aspersPLO[0]);
                $PLO = $PLOS[1] .' x' . $PLOS[0];
            }
                    

           $game = array($NLH,$PLO);

            return $game;

            
        }else{

            print "regex not met!!!!!! <br> <br>" . "\n";
        }



    }


    private function updateCash($nlh,$plo,$casino,$id,$date,$url)
    {
         Cash::where('update', '=', 1)->where('casino_id','=',$id)->update(['update' => 0]);

        if ( $nlh ) {

       
            foreach ($nlh as $n) { 

                $nlhs = explode("x" ,$n);

                $stakes =  preg_replace('/[^A-Za-z0-9\-\/]/', '',$nlhs[0]);
                print $stakes;
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

        return;

    }
}   
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

class AspersTweets extends Command
{
    /////// 
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'aspers:tweets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'cash games for aspers';

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
        $aspers = Twitter::getUserTimeline(['screen_name' => 'AspersPoker', 'count' => 10, 'format' => 'array']);
        $aspers = $aspers[0]{'text'};
        //remove any unwanted characters
        $aspers = str_replace(chr(194)," ",$aspers);

        //Remove links and unwanted hashtags
        $sanitizeTweet = $this->sanitizeTweet($aspers);

        //Return all the current games
        $getGames = $this->tweetFormat($sanitizeTweet);


        echo  $sanitizeTweet . "<br> ";

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
        $regex1 = '/(\d\/\s?\W?\d*\s?\w\s?\d)/'; 
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
            $PLOS = str_replace('(', 'x ',$aspersPLO[0]);
            $PLO = str_replace(')', '',$PLOS);

            // Update the databse
            $this->updateCash($NLH,$PLO,'Aspers',1,'');

            return $split;


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

            // Update the databse
            $this->updateCash($NLH,$PLO,'Aspers',1,'');

            return $split;

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

            $NLH = $aspersNLH[0];
            $PLO = $aspersPLO[0];

            $this->updateCash($NLH,$PLO,'Aspers',1,'change');

            return $split;

            
        }else{

            print "regex not met!!!!!! <br> <br>" . "\n";
        }



    }


    private function updateCash($nlh,$plo,$casino,$id,$change)
    {
         Cash::where('update', '=', 1)->where('casino_id','=',$id)->update(['update' => 0]);

        if ( $nlh ) {

       
            foreach ($nlh as $n) { 

                if ($change == 'change') {
                    $NLHS = explode('x', $n);
                    $n = $NLHS[1] .' x' . $NLHS[0];
                    
                }

                $nlhs = explode("x" ,$n);

                $stakes =  preg_replace('/[^A-Za-z0-9\-\/]/', '',$nlhs[0]);

                Cash::create(array(
                        'casino' => $casino,
                        'casino_id' => $id,
                        'tables' => $nlhs[1],
                        'game' => 'NLH',
                        'stakes' => $stakes,
                        'update' => 1

                ));

            }

            
        }

        if ( $plo ) {

            foreach ($plo as $p) { 

                if($change){
                    $PLOS = explode('x', $p);
                    $p = $PLOS[1] .' x' . $PLOS[0];
                }
                $plos = explode("x" ,$p);

                $stakes =  preg_replace('/[^A-Za-z0-9\-\/]/', '',$plos[0]);

                Cash::create(array(
                        'casino' => $casino,
                        'casino_id' => $id,
                        'tables' => $plos[1],
                        'game' => 'PLO',
                        'stakes' => $stakes,
                        'update' => 1

                ));

            }

            
        }

        return;

    }
}   

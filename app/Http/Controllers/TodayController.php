<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Cash;
use App\Tournaments;
use App\Weekly;
use View;
use DB;
use App\Http\Requests;
use Sunra\PhpSimple\HtmlDomParser;
use Carbon\Carbon;


class TodayController extends Controller
{
     


    public function getIndex()
    {
        // make all this an array
        // Todays date
        $date = Carbon::today();

        $cash = DB::table('cashGames')->where('update', '=',1)->get();

        $aspers = DB::table('cashGames')->where('update', '=',1)
                        ->where('casino_id', '=', 1)
                        ->orderBy('tables','desc')
                        ->get();

        $empire = DB::table('cashGames')->where('update', '=',1)
                        ->where('casino_id', '=', 2)
                        ->get();

        $hippo = DB::table('cashGames')->where('update', '=',1)
                        ->where('casino_id', '=', 3)
                        ->orderBy('tables','desc')
                        ->get();

        // $golden = DB::table('cashGames')->where('update', '=',1)
        //                 ->where('casino_id', '=', 4)
        //                 ->orderBy('tables','desc')
        //                 ->get();

        $vic = DB::table('cashGames')->where('update', '=',1)
                        ->where('casino_id', '=', 5)
                        ->orderBy('tables','desc')
                        ->get();

        $tournaments = Tournaments::where('date','=',$date)
                        ->orderBy('start','asc')
                        ->get();


        $now =  new \DateTime();

        $casinos = array(
                        'aspers' => $aspers, 
                        'empire' => $empire, 
                        'hippo' => $hippo, 
                        // 'golden' => $golden, 
                        'vic' => $vic
                    );

        $results = array();
        foreach ($casinos as $name => $c) {
            if($c){
                $updated = $c[0]->tweet_time;
                $newDate = new \DateTime($updated);
                $diff = $newDate->diff($now);
                $lastUpdated = $diff->format("%h");
                $variableName = $name . "_updated";
                $results[$variableName] = $lastUpdated;

            }
            
        }
        


        return View::make('today')
                    ->with('tournaments',$tournaments)
                    ->with('aspers',$aspers)
                    ->with('empire',$empire)
                    ->with('hippo',$hippo)
                    // ->with('golden',$golden)
                    ->with('vic',$vic)
                    ->with('games',$cash)
                    ->with('results',$results);

    }

}


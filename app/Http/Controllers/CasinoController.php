<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Tournaments;
use App\Casino;
use App\Cash;
use View;
use DB;
use App\Http\Requests;
use Carbon\Carbon;
use Thujohn\Twitter\Facades\Twitter;


class CasinoController extends Controller
{

	/*===========================================
	=            Todays tournaments             =
	===========================================*/
	
	
	public function getIndex()
	{
		$casinos = Casino::all();

		return View::make('casino.index')
         					->with('casinos',$casinos);

	}

	public function getProfile($casino)
	{
		$week_start = Carbon::now()->startOfWeek();
		$week_end = Carbon::now()->endOfWeek();

		$casino = Casino::where('url', $casino)
						->get();

		$tournaments = Tournaments::where('casino_id', '=', $casino[0]->id)
						->whereBetween('date',[$week_start,$week_end])
						->orderBy('date','asc')
						->get();
		
		$cash 		= DB::table('cashGames')->where('update', '=',1)
                        ->where('casino_id', '=', $casino[0]->id)
                        ->orderBy('tables','desc')
                        ->get();

		return View::make('casino.profile')
						->with('tournaments',$tournaments)
         				->with('cashGames',$cash)
         				->with('casino',$casino);
	}

	public function getTournaments($casino)
	{
		return View::make('casino.tournaments');
	}


}	

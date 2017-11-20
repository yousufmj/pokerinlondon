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
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;


class AdminController extends Controller
{

	/*===========================================
	=            Todays tournaments             =
	===========================================*/
	
	
	public function manageCash()
	{
		$cash = Cash::allGames()->get();
		// print_r($aspers);
		return View::make('admin.manage_cash')
				->with('cash',$cash);

	}

	public function edit(Request $request)
	{
		 //Cash::where('update', '=', 1)->update(['update' => 0]);
		 $post = $request->all();

	foreach ($post as $p ){
		echo $p[0] . "is casino <br>";
		echo $p[1] . "is stakes <br>";
		echo $p[2] . "is tables <br>";
		echo $p[3] . "is game <br>";
	}

		 print_r($post);

	}

	


}	

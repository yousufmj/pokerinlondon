<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tournaments extends Model
{
    protected $table = 'tournaments';

    protected $fillable = ['casino', 'casino_id' ,'event', 'stack', 'clock', 'date', 'buyin', 'start'];

    public function casino()
    {
    	return $this->belongsTo('App\Casino');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weekly extends Model
{
    protected $table = 'weekly_stats';

    protected $fillable = ['casino_id', 'tables', 'stakes', 'game', 'weekday', 'update_time'];

    public function casino()
    {
    	return $this->belongsTo('App\Casino');
    }
}

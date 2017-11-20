<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cash extends Model
{
    protected $table = 'cashGames';

    protected $fillable = ['casino_id', 'tables', 'stakes', 'update', 'game', 'tweet_time', 'url'];

    public function casino()
    {
    	return $this->belongsTo('App\Casino');
    }

    public function scopeAllGames($query)
    {
        return $query->where('update', '=', 1)
                    ->join('casino', 'casino_id', '=', 'casino.id')
                    ->orderBy('stakes');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Casino extends Model
{
    protected $table = 'casino';

    protected $fillable = ['name', 'facebook', 'twitter'];

    public function cashgames()
    {
    	return $this->hasMany('App\Cash');
    }

    public function tournaments()
    {
    	return $this->hasMany('App\Tournaments');
    }

    public function weeklyStats()
    {
    	return $this->hasMany('App\Weekly');
    }
}

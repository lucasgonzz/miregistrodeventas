<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    
    protected $fillable = [
    	'user_id',
    	'client_id',
    ];

    public function articles() {
        return $this->belongsToMany('App\Article')->withPivot('amount', 'cost', 'price');
    }

    public function client() {
        return $this->belongsTo('App\Client');
    }
}

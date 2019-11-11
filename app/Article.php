<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    
    protected $fillable = [
        'user_id',
    	'bar_code',
    	'name',
        'cost',
    	'stock',
    	'price',
    	'last_price',
    	'created_at',
    ];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function sub_user() {
        return $this->belongsTo('App\User', 'sub_user_id');
    }
    
    public function updated_by() {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    public function sales() {
        return $this->belongsToMany('App\Sale')->latest();
    }
    
    public function providers(){
        return $this->belongsToMany('App\Provider')
                                                    ->withPivot('amount', 'cost', 'price')
                                                    ->withTimestamps()
                                                    ->orderBy('id', 'DESC');
    }
}

<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BarCode extends Model
{
   protected $fillable = ['name', 'amount', 'user_id'];
}

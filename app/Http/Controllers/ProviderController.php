<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Provider;

class ProviderController extends Controller
{
    function index() {
    	return Provider::where('user_id', Auth()->user()->id)->get();
    }
}

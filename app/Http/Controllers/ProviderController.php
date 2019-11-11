<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Provider;

class ProviderController extends Controller
{
    function index() {
    	return Provider::where('user_id', Auth()->user()->id)->get();
    }

    function store(Request $request) {
    	Provider::create([
    		'name' => ucwords($request->provider['name']),
    		'user_id' => Auth()->user()->id
    	]);
    }

    function delete($id) {
    	Provider::find($id)->delete();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
    function index() {
    	return Client::where('user_id', Auth()->user()->id)->get();
    }

    function store(Request $request) {
    	$client = Client::create([
    		'name' => ucwords($request->client['name']),
    		'user_id' => Auth()->user()->id
    	]);
    	return $client;
    }

    function delete($id) {
    	Client::find($id)->delete();
    }
}

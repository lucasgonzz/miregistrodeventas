<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;

class ClientController extends Controller
{
    function getArticleOwnerId() {
        $user = Auth()->user();
        if (is_null($user->belongs_to)) {
            return $user->id;
        } else {
            return $user->belongs_to;
        }
    }

    function index() {
    	return Client::where('user_id', $this->getArticleOwnerId())->get();
    }

    function store(Request $request) {
    	$client = Client::create([
    		'name' => ucwords($request->client['name']),
    		'user_id' => $this->getArticleOwnerId()
    	]);
    	return $client;
    }

    function delete($id) {
    	Client::find($id)->delete();
    }
}

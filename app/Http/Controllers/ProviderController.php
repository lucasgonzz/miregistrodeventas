<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Provider;

class ProviderController extends Controller
{
    function index() {
    	return Provider::where('user_id', Auth()->user()->id)->get();
    }

    function store($provider_name) {
        // return Auth()->user()->id;
        $provider = Provider::create([
            'name' => ucwords($provider_name),
            'user_id' => Auth()->user()->id,
        ]);
        // return $request->provider['name'];
        // $provider = new Provider;
        // $provider->name = ucwords($request->provider['name']);
        // $provider->user_id = Auth()->user()->id;
        // $provider->save();
        return $provider;
    }

    function delete($id) {
    	Provider::find($id)->delete();
    }
}

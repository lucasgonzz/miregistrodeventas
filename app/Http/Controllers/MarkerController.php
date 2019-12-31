<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marker;

class MarkerController extends Controller
{

	function index() {
		return Marker::where('user_id', Auth()->user()->id)
						->whereDoesntHave('markerGroup')
						->with('article')
						->get();
	}

	function store(Request $request) {
		Marker::create([
			'user_id' => Auth()->user()->id,
			'article_id' => $request->article_id,
		]);
	}

	function delete($id) {
		Marker::find($id)->delete();
	}
}

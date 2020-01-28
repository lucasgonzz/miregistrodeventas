<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Marker;

class MarkerController extends Controller
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
		return Marker::where('user_id', $this->getArticleOwnerId())
						->whereDoesntHave('markerGroup')
						->with('article')
						->get();
	}

	function store(Request $request) {
		Marker::create([
			'user_id' => $this->getArticleOwnerId(),
			'article_id' => $request->article_id,
		]);
	}

	function delete($id) {
		Marker::find($id)->delete();
	}
}

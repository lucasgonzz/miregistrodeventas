<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MarkerGroup;
use App\Marker;
use App\Article;

class MarkerGroupController extends Controller
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
		return MarkerGroup::where('user_id', $this->getArticleOwnerId())
							->with('markers.article')
							->get();
	}

	function addMarkerToGroup($marker_group_id, $article_id) {
		$marker = Marker::create([
			'user_id' => $this->getArticleOwnerId(),
			'article_id' => $article_id,
			'marker_group_id' => $marker_group_id,
		]);
		// $article = Article::find($article_id);
		// $article->marker = 1;
		// $article->save();
	}

    function store(Request $request) {
    	// return $request->marker_group;
    	MarkerGroup::create([
    		'user_id' => $this->getArticleOwnerId(),
    		'name' => ucwords($request->marker_group['name']),
    	]);
    }

    function delete($id) {
    	$marker_group = MarkerGroup::find($id);
    	foreach ($marker_group->markers as $marker) {
    		$marker->delete();
    	}
    	$marker_group->delete();
    }
}

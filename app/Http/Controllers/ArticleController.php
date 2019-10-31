<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    function index() {
    	$articles = Article::where('user_id', Auth()->user()->id)->paginate(10);
    	return [
                'pagination' => [
                    'total' => $articles->total(),
                    'current_page' => $articles->currentPage(),
                    'per_page' => $articles->perPage(),
                    'last_page' => $articles->lastPage(),
                    'from' => $articles->firstItem(),
                    'to' => $articles->lastPage(),
                ],
                'articles' => $articles 
            ];
    	// return $articles;
    }
}

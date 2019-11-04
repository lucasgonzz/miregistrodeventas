<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    function index() {
    	$articles = Article::where('user_id', Auth()->user()->id)
                            ->with('providers')
                            ->paginate(10);
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
    function filter(Request $request) {
        $mostrar = $request->mostrar;
        $ordenar = $request->ordenar;
        $precio_entre = $request->precio_entre;

        $articles = Article::where('user_id', Auth()->user()->id);

        if ($mostrar == 'desactualizados') {
                $fecha_actual = date('d-m-Y');
                $hace_6_meses = date('d-m-Y', strtotime($fecha_actual."- 6 month"));
                $articles = $articles->whereDate('updated_at', '<=', $hace_6_meses);
        }

        if ($mostrar == 'no-vendidos') {
            $articles = $articles->doesntHave('sales');
        }

        if ($mostrar == 'no-stock') {
            $articles = $articles->where('stock', 0);
        }

        if ($ordenar == 'nuevos-viejos') {
            $articles = $articles->orderBy('id', 'DESC');
        }

        if ($ordenar == 'viejos-nuevos') {
            $articles = $articles->orderBy('id', 'ASC');
        }

        if ($ordenar == 'caros-baratos') {
            $articles = $articles->orderBy('price', 'DESC');
        }

        if ($ordenar == 'baratos-caros') {
            $articles = $articles->orderBy('price', 'ASC');
        }

        if (!is_null($precio_entre['min']) && !is_null($precio_entre['max'])) {
            $articles = $articles->where('price', '>', $precio_entre['min'])
                                    ->where('price', '<', $precio_entre['max']);
        }

        return $articles->get();
    }

    function search($query) {
        $articles = Article::where('bar_code', $query)->get();
        if (count($articles) == 0) {
            $articles = Article::where('name', 'LIKE', "%$query%")->get();
        } 
        return $articles;
    }

    function pre_search($query) {
        $articles = Article::where('name', 'LIKE', "%$query%")->get();
        return $articles;

    }

    function update(Request $request, $id) {
        $article = Article::find($id);
        $updated_article = $request->article;

        if ($article->price != $updated_article['price']) {
            // return 'entro';
            $article->previus_price = $article->price;
        }
        if (!$updated_article['act_fecha']) {
            $article->timestamps = false;
        } 
        $article->name = $updated_article['name'];
        $article->cost = $updated_article['cost'];
        $article->price = $updated_article['price'];
        $article->stock = $updated_article['stock'];
        $article->save();
    }

    function destroy($id) {
        Article::find($id)->delete();
    }
}

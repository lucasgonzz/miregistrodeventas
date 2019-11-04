<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
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

    // function filter() {
    function filter(Request $request) {
        $user = Auth()->user();
        $mostrar = $request->mostrar;
        $ordenar = $request->ordenar;
        $precio_entre = $request->precio_entre;
        // $mostrar = '';
        // $ordenar = '';
        // $precio_entre = '';
        // $providers = [1, 2];

        $articles = Article::where('user_id', $user->id);

        // $articles = Article::with('providers');


        // return $articles->get();

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

        if ($user->hasRole('commerce')) {
            $providers = $request->providers;
            if (count($providers) == 1) {
                $articles = $articles->with('providers')->whereHas('providers', function(Builder $query) use ($providers) {
                    $query->where('name', $providers[0]);
                });
            } else {
                $articles = $articles->with('providers')->whereHas('providers', function(Builder $query) use ($providers) {
                    $query->whereIn('name', $providers);
                });
            }
        }

        // return $articles->get();

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

        // if (!is_null($precio_entre['min']) && !is_null($precio_entre['max'])) {
        //     $articles = $articles->where('price', '>', $precio_entre['min'])
        //                             ->where('price', '<', $precio_entre['max']);
        // }

        return $articles->get();
    }

    function search($query) {
        $user = Auth()->user();
        $articles = Article::where('user_id', Auth()->user()->id)
                            ->where('bar_code', $query);
        if ($user->hasRole('commerce')) {
            $articles = $articles->with('providers');
        }
        $articles = $articles->get();

        if (count($articles) == 0) {
            $articles = Article::where('user_id', Auth()->user()->id)
                                ->where('name', 'LIKE', "%$query%");
            if ($user->hasRole('commerce')) {
                $articles = $articles->with('providers');
            }
            $articles = $articles->get();
        } 

        return $articles;
    }

    function pre_search($query) {
        return Article::where('user_id', Auth()->user()->id)
                            ->where('name', 'LIKE', "%$query%")->get();
    }

    function update(Request $request, $id) {
        $article = Article::find($id);
        $updated_article = $request->article;

        if ($article->price != $updated_article['price']) {
            $article->previus_price = $article->price;
        }
        if (!$updated_article['act_fecha']) {
            $article->timestamps = false;
        } 
        if (Auth()->user()->hasRole('commerce')) {
            $article->providers()->sync($updated_article['providers']);
        }
        $article->name =  ucwords($updated_article['name']);
        $article->cost = $updated_article['cost'];
        $article->price = $updated_article['price'];
        $article->stock = $updated_article['stock'];
        $article->save();
    }

    function destroy($id) {
        Article::find($id)->delete();
    }
}

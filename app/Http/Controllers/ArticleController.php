<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use App\Article;
use App\BarCode;

use App\Exports\ArticlesExport;
use App\Imports\ArticlesImport;
use Maatwebsite\Excel\Facades\Excel;

class ArticleController extends Controller
{
    function index() {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
        	$articles = Article::where('user_id',$user->id)
                                ->orderBy('id', 'DESC')
                                ->with('marker')
                                ->with(array('providers' => function($q) {
                                    $q->orderBy('created_at', 'DESC');
                                }))
                                ->paginate(10);
        } else {
            $articles = Article::where('user_id',$user->id)
                                ->orderBy('id', 'DESC')
                                ->with('marker')
                                ->paginate(10);
        }
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
    }

    function withMarker($id) {
        return Article::where('id', $id)
                        ->with('marker')
                        ->first();
    }

    // function createMarker($id) {
    //     $article = Article::find($id);
    //     $article->marker = 1;
    //     $article->save();
    // }

    // function deleteMarker($id) {
    //     $article = Article::find($id);
    //     $article->marker = 0;
    //     $article->save();
    // }

    function getAvailables() {
        return Article::where('user_id', Auth()->user()->id)
                        ->select('bar_code', 'name', 'uncontable')
                        ->get();
    }

    function getMarkers() {
        return Article::where('user_id', Auth()->user()->id)
                        ->where('marker', 1)
                        ->get();
    }

    function getByBarCode($bar_code) {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
            return Article::where('user_id', $user->id)
                            ->where('bar_code', $bar_code)
                            ->with('providers')
                            ->first();
        } else {
            return Article::where('user_id', $user->id)
                            ->where('bar_code', $bar_code)
                            ->first();
        }
    }

    function getByName($name) {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
            return Article::where('user_id', $user->id)
                            ->where('name', $name)
                            ->with('providers')
                            ->first();
        } else {
            return Article::where('user_id', $user->id)
                            ->where('name', $name)
                            ->first();
        }
    }

    function getByIds($articles_id) {
        $articles_id = explode('-', $articles_id);
        $articles = [];
        foreach ($articles_id as $article_id) {
            $articles[]  = Article::find($article_id);
        }
        return $articles;
    }

    function getBarCodes() {
        return Article::where('user_id', Auth()->user()->id)
                        ->pluck('bar_code');
    }

    function getNames() {
        return Article::where('user_id', Auth()->user()->id)
                        // ->whereNull('bar_code')
                        ->pluck('name');
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

    function previusNext($index) {
        $user = Auth()->user();
        if ($user->hasRole('commerce')) {
            $articles = Article::where('user_id', Auth()->user()->id)
                                ->orderBy('id', 'DESC')
                                ->with('providers')
                                ->take($index)
                                ->get();
        } else {
            $articles = Article::where('user_id', Auth()->user()->id)
                                ->orderBy('id', 'DESC')
                                ->take($index)
                                ->get();
        }
        return $articles[$index-1];
    }

    function createOffer(Request $request) {
        foreach ($request->articles as $article) {
            $_article = Article::find($article['id']);
            $_article->offer_price = $article['offer_price'];
            $_article->save();
        }
        // foreach ($request->articles_id as $article_id) {
        //     $article = Article::find($article_id);
        //     $article->offer_price = $article->price - round(($request->porcentage/100)*$article->price, 2);
        //     $article->save();
        // }
    }

    function update(Request $request, $id) {
        $article = Article::find($id);
        $updated_article = $request->article;

        if (isset($updated_article['new_bar_code'])) {
            $article->bar_code = $updated_article['new_bar_code'];
        }

        if (isset($updated_article['offer_price'])) {
            $article->offer_price = $updated_article['offer_price'];
        }

        if ($article->price != $updated_article['price']) {
            $article->previus_price = $article->price;
        }
        if (!$updated_article['act_fecha']) {
            $article->timestamps = false;
        } 
        $article->name =  ucwords($updated_article['name']);
        $article->cost = $updated_article['cost'];
        $article->price = $updated_article['price'];
        $article->stock += (int)$updated_article['new_stock'];
        $article->save();
        if (Auth()->user()->hasRole('commerce')) {
            $article->providers()
                                ->attach(
                                    $updated_article['provider'], 
                                    [
                                        'amount' => $updated_article['new_stock'],
                                        'cost' => $updated_article['cost'],
                                        'price' => $updated_article['price'],
                                    ]);
        }
        return $article;
    }

    function updateByPorcentage(Request $request) {
        // return $request->decimals;
        // if ($request->round == 'up') {
        //     $round = PHP_ROUND_HALF_UP;
        // } else if ($request->round == 'down') {
        //     $round = PHP_ROUND_HALF_DOWN;
        // }
        $decimals = (bool)$request->decimals;
        $articles_ids = $request->articles_ids;
        foreach ($articles_ids as $article_id) {
            $article = Article::find($article_id);
            if (!empty($request->cost)) {
                if($decimals) {
                    $article->cost += round(($request->cost/100)*$article->cost, 2);
                } else {
                    $article->cost += round(($request->cost/100)*$article->cost, 0);
                }
            }
            if (!empty($request->price)) {
                $article->previus_price = $article->price;
                if($decimals) {
                    $article->price += round(($request->price/100)*$article->price, 2);
                } else {
                    $article->price += round(($request->price/100)*$article->price, 0);
                }
            }
            $article->save();
        }
    }

    function store(Request $request) {
        $errores = false;
        $user = Auth()->user();
        $article = new Article();
        if ($request->article['is_uncontable']) {
            $article->uncontable = 1;
            $article->measurement = $request->article['measurement'];
        }
        $article->bar_code = $request->article['bar_code'];
        $article->name = ucwords($request->article['name']);
        $article->cost = $request->article['cost'];
        $article->price = $request->article['price'];
        $article->previus_price = 0;
        $article->stock = $request->article['stock'];
        $article->user_id = $user->id;

        $date = date('Y-m-d');

        // Revisar este codigo de la fecha
        if ($request->article['created_at'] != $date) {
            $article->created_at = $request->article['created_at'];
            $article->updated_at = $request->article['created_at'];
        }
        $article->save();
        // if (!$article->save()) {
        //     $errores = true;
        // }
        if ($user->hasRole('commerce')) {
            $article->providers()->attach($request->article['provider'], [
                                            'amount' => $request->article['stock'],
                                            'cost' => $request->article['cost'],
                                            'price' => $request->article['price']
                                        ]);
        }

        $bar_code = BarCode::where('user_id', $user->id)
                                ->where('name', $request->article['bar_code'])
                                ->first();
        if ($bar_code === null) {
            // return 'No existe';
        } else {
            $bar_code->article_id = $article->id;
            $bar_code->save();
            // return 'ASD';
        }
        return $article;
    }


    function destroy($id) {
        Article::find($id)->delete();
    }

    function destroyArticles($ids) {
        foreach (explode('-', $ids) as $article_id) {
            Article::find($article_id)->delete();
        }
    }

    function deleteOffer($id) {
        $article = Article::find($id);
        $article->offer_price = null;
        $article->save();
    }

    function filter(Request $request) {
        $user = Auth()->user();
        $mostrar = $request->mostrar;
        $ordenar = $request->ordenar;
        $precio_entre = $request->precio_entre;
        $precio_minimo = (float)$request->precio_entre['min'];
        $precio_maximo = (float)$request->precio_entre['max'];
        // return gettype((float)$precio_entre['min']);
        // return (float)$request->precio_entre['max'];

        $articles = Article::where('user_id', $user->id);

        // Mostrar
        if ($mostrar == 'oferta') {
            $articles = $articles->whereNotNull('offer_price');
        }
        else if ($mostrar == 'marker') {
                $fecha_actual = date('d-m-Y');
                $hace_6_meses = date('d-m-Y', strtotime($fecha_actual."- 6 month"));
                $articles = $articles->where('marker', 1);
        }
        else if ($mostrar == 'desactualizados') {
                $fecha_actual = date('d-m-Y');
                $hace_6_meses = date('d-m-Y', strtotime($fecha_actual."- 6 month"));
                $articles = $articles->whereDate('updated_at', '<=', $hace_6_meses);
        }
        else if ($mostrar == 'no-vendidos') {
            $articles = $articles->doesntHave('sales');
        }
        else if ($mostrar == 'no-stock') {
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

        // Ordenar
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

        if ($precio_minimo != '' && $precio_maximo != '') {
            $articles = $articles->whereBetween('offer_price', 
                                                    [$precio_minimo, $precio_maximo]
                                                )->orWhereBetween('price', 
                                                    [$precio_minimo, $precio_maximo]
                                                );
        }

        return $articles->get();
    }

    function export() {
        return Excel::download(new ArticlesExport, 'miregistrodeventas-articulos.xlsx');
    }

    function import(Request $request) {
        (new ArticlesImport)->import($request->exel);
        // Excel::import(new ArticlesImport, $request->exel);
        if (Auth()->user()->hasRole('provider')) {
            return redirect()->route('listado.provider')->with('success', 'Importacion realizada con exito');
        } else {
            return redirect()->route('listado.commerce')->with('success', 'Importacion realizada con exito');
        }
    }
}

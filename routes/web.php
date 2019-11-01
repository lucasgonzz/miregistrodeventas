<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'MainController@index');

Route::get('login', 'Auth\LoginController@showLoginForm')->name('show-login-form')->middleware('guest');
Route::post('login', 'Auth\LoginController@login')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('user/password', 'UserController@password')->name('change-password');
Route::post('user/password', 'UserController@update_password')->name('password');

// Auth::routes();


// Mayoristas
Route::group(['middleware' => ['has.role:provider']], function () {
	Route::get('/mayoristas/vender', 'MainController@provider_vender')->name('vender.provider');
	Route::get('/mayoristas/ingresar', 'MainController@provider_ingresar')->name('ingresar.provider');
	Route::get('/mayoristas/listado', 'MainController@provider_listado')->name('listado.provider');
	Route::get('/mayoristas/ventas', 'MainController@provider_ventas')->name('ventas.provider');
	Route::get('/mayoristas/articles', 'ArticleController@index');

	// Listado
	Route::post('/mayoristas/articles/filter', 'ArticleController@filter');
	Route::get('/mayoristas/articles/search/{query}', 'ArticleController@search');
	Route::get('/mayoristas/articles/pre-search/{query}', 'ArticleController@pre_search');
	Route::put('/mayoristas/articles/{id}', 'ArticleController@update');
	Route::delete('/mayoristas/articles/{id}', 'ArticleController@destroy');

});

// Comercios
Route::group(['middleware' => ['has.role:commerce']], function () {
	Route::get('/comercios/vender', 'MainController@commerce_vender')->name('vender.commerce');
	Route::get('/comercios/ingresar', 'MainController@commerce_ingresar')->name('ingresar.commerce');
	Route::get('/comercios/listado', 'MainController@commerce_listado')->name('listado.commerce');
	Route::get('/comercios/ventas', 'MainController@commerce_ventas')->name('ventas.commerce');
	Route::get('/comercios/articles', 'ArticleController@index');

	Route::post('/comercios/articles/filter', 'ArticleController@filter');
	Route::get('/comercios/configuracion', 'MainController@commerce_config')->name('commerce.config');
});

// Comunes

Route::get('codigos-de-barra', 'MainController@codigos_de_barra')->name('bar-codes');
Route::get('empleados', 'MainController@empleados')->name('empleados');
Route::get('permissions', 'PermissionController@index');

Route::get('pdf', 'PdfController@index');
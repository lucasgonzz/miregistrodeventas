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

	// Codigos de barra
	Route::get('bar-codes', 'BarCodeController@index');

	// Ingresar
	Route::post('/mayoristas/articles', 'ArticleController@store');
	Route::get('/mayoristas/articles/get-by-bar-code/{id}', 'ArticleController@getByBarCode');
	Route::get('/mayoristas/articles/bar-codes', 'ArticleController@BarCodes');

	// Listado
	Route::post('/mayoristas/articles/filter', 'ArticleController@filter');
	Route::get('/mayoristas/articles/search/{query}', 'ArticleController@search');
	Route::get('/mayoristas/articles/pre-search/{query}', 'ArticleController@pre_search');
	Route::put('/mayoristas/articles/{id}', 'ArticleController@update');
	Route::delete('/mayoristas/articles/{id}', 'ArticleController@destroy');
	Route::get('/mayoristas/bar-codes/generated', 'BarCodeController@generated');

	// Listado exportar
	Route::get('/mayoristas/pdf/{columns}/{articles_ids}/{orientation}/{header?}', 'PdfController@articles');
	Route::get('/mayoristas/articles/exel', 'ArticleController@export')->name('provider.exel');

	// Vender
	Route::post('mayoristas/sales', 'SaleController@store');
	Route::get('mayoristas/sales/cliente/{company_name}/{borders}/{per_page}/{sale_id}', 'PdfController@ticket_client');
	Route::get('mayoristas/sales/comercio/{company_name}/{borders}/{per_page}/{sale_id}', 'PdfController@ticket_commerce');
	// Vender - Clientes
	Route::get('mayoristas/clients', 'ClientController@index');
	Route::post('mayoristas/clients', 'ClientController@store');
	Route::delete('mayoristas/clients/{id}', 'ClientController@delete');

	Route::post('/mayoristas/articles/import/exel', 'ArticleController@import');

});

// Comercios
Route::group(['middleware' => ['has.role:commerce']], function () {
	Route::get('/comercios/vender', 'MainController@commerce_vender')->name('vender.commerce');
	Route::get('/comercios/ingresar', 'MainController@commerce_ingresar')->name('ingresar.commerce');
	Route::get('/comercios/listado', 'MainController@commerce_listado')->name('listado.commerce');
	Route::get('/comercios/ventas', 'MainController@commerce_ventas')->name('ventas.commerce');
	Route::get('/comercios/articles', 'ArticleController@index');

	// Codigos de barra
	Route::get('bar-codes', 'BarCodeController@index');

	// Ingresar
	Route::post('/comercios/articles', 'ArticleController@store');
	Route::get('/comercios/providers', 'ProviderController@index');
	Route::post('/comercios/providers', 'ProviderController@store');
	Route::delete('/comercios/providers/{id}', 'ProviderController@delete');
	Route::get('/comercios/articles/get-by-bar-code/{id}', 'ArticleController@getByBarCode');
	Route::get('/comercios/articles/bar-codes', 'ArticleController@BarCodes');
	Route::get('/comercios/articles/previus-next/{index}', 'ArticleController@previusNext');
	Route::get('/comercios/bar-codes/generated', 'BarCodeController@generated');

	// Listado
	Route::post('/comercios/articles/filter', 'ArticleController@filter');
	Route::get('/comercios/articles/search/{query}', 'ArticleController@search');
	Route::get('/comercios/articles/pre-search/{query}', 'ArticleController@pre_search');
	Route::get('/comercios/providers', 'ProviderController@index');
	Route::put('/comercios/articles/{id}', 'ArticleController@update');
	Route::delete('/comercios/articles/{id}', 'ArticleController@destroy');

	// Listado exportar
	Route::get('/comercios/configuracion', 'MainController@commerce_config')->name('commerce.config');
	Route::get('/comercios/pdf/{orientation}/{header}/{columns}/{articles_ids}', 'PdfController@articles');
	Route::get('/comercios/articles/exel', 'ArticleController@export')->name('commerce.exel');
});

// Comunes

Route::get('codigos-de-barra', 'MainController@codigos_de_barra')->name('bar-codes');
Route::get('empleados', 'MainController@empleados')->name('empleados');
Route::get('permissions', 'PermissionController@index');
Route::get('/articles/exel', 'ArticleController@export')->name('exel');
Route::post('/articles/import/exel', 'ArticleController@import');

Route::get('pdf', 'PdfController@index');
Route::get('bar-codes', 'BarCodeController@index');
Route::post('bar-codes', 'BarCodeController@store');
Route::get('bar-codes/{bar_code}/{amount}/{size}/{text}', 'BarCodeController@store');
Route::delete('bar-codes/{id}', 'BarCodeController@delete');

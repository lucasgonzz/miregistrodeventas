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

Route::group(['middleware' => ['has.role:provider']], function () {
	Route::get('/mayoristas/vender', 'MainController@provider_vender')->name('vender.provider');
	Route::get('/mayoristas/ingresar', 'MainController@provider_ingresar')->name('ingresar.provider');
	Route::get('/mayoristas/listado', 'MainController@provider_listado')->name('listado.provider');
});

Route::group(['middleware' => ['has.role:commerce']], function () {
	Route::get('/comercio/vender', 'MainController@commerce_vender')->name('vender.commerce');
	Route::get('/comercio/ingresar', 'MainController@commerce_ingresar')->name('ingresar.commerce');
	Route::get('/comercio/listado', 'MainController@commerce_listado')->name('listado.commerce');
});

// Route::get('/ingresar', 'MainController@ingresar')->name('ingresar');
// Route::get('/listado', 'MainController@listado')->name('listado');

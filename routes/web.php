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

Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['has.role:provider']], function () {
	// Route::get('admin/general', 'AdminController@general'); 
	Route::get('/mayoristas/vender', 'MainController@vender_provider')->name('vender.provider');
});
Route::group(['middleware' => ['has.role:commerce']], function () {
	// Route::get('admin/general', 'AdminController@general'); 
	Route::get('/comercio/vender', 'MainController@vender_commerce')->name('vender.commerce');
});

Route::get('/nuevo', 'MainController@nuevo')->name('nuevo');
Route::get('/listado', 'MainController@listado')->name('listado');

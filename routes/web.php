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
Route::get('register', 'Auth\RegisterController@showRegisterForm')->name('show-register-form')->middleware('guest');
Route::post('register', 'Auth\RegisterController@register')->name('register');
Route::get('login-owner', 'Auth\LoginController@login_owner')->name('login-owner');
Route::post('login-employee', 'Auth\LoginController@login_employee')->name('login-employee');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Auth::routes();


// Mayoristas
Route::group(['middleware' => ['has.role:provider']], function () {

	// Principal
		Route::get('/mayoristas/vender', 'MainController@provider_vender')->name('vender.provider');
		Route::get('/mayoristas/ingresar', 'MainController@provider_ingresar')->name('ingresar.provider');
		Route::get('/mayoristas/listado', 'MainController@provider_listado')->name('listado.provider');
		Route::get('/mayoristas/ventas', 'MainController@provider_ventas')->name('ventas.provider');
		Route::get('/mayoristas/codigos-de-barra', 'MainController@codigos_de_barra')->name('bar-codes.provider');
		Route::get('/mayoristas/empleados', 'MainController@provider_empleados')->name('empleados.provider');
	
	// Codigos de barra
		Route::get('/mayoristas/bar-codes', 'BarCodeController@index');
		Route::post('/mayoristas/bar-codes', 'BarCodeController@store');
		Route::get('/mayoristas/bar-codes/{bar_code}/{amount}/{size}/{text}', 'BarCodeController@store');
		Route::delete('/mayoristas/bar-codes/{id}', 'BarCodeController@delete');

	// Ingresar
		Route::post('/mayoristas/articles', 'ArticleController@store');
		Route::get('/mayoristas/articles/get-by-bar-code/{bar_code}', 'ArticleController@getByBarCode');
		Route::get('/mayoristas/articles/bar-codes', 'ArticleController@getBarCodes');
		Route::get('/mayoristas/articles/names', 'ArticleController@getNames');
		Route::get('/mayoristas/articles/previus-next/{index}', 'ArticleController@previusNext');

	// Listado
		Route::get('/mayoristas/articles', 'ArticleController@index');
		Route::post('/mayoristas/articles/filter', 'ArticleController@filter');
		Route::get('/mayoristas/articles/search/{query}', 'ArticleController@search');
		Route::get('/mayoristas/articles/pre-search/{query}', 'ArticleController@pre_search');
		Route::put('/mayoristas/articles/{id}', 'ArticleController@update');
		Route::delete('/mayoristas/articles/{id}', 'ArticleController@destroy');
		Route::delete('/mayoristas/articles/delete-articles/{ids}', 'ArticleController@destroyArticles');
		Route::get('/mayoristas/bar-codes/generated', 'BarCodeController@generated');
		Route::get('/mayoristas/imprimir-precios/{articles_id}/{company_name}', 'PdfController@printTicket');
		Route::post('/mayoristas/articles/update-by-porcentage', 'ArticleController@updateByPorcentage');
		Route::post('/mayoristas/articles/create-offer', 'ArticleController@createOffer');
		Route::get('/mayoristas/articles/get-by-ids/{articles_id}', 'ArticleController@getByIds');
		Route::get('/mayoristas/articles/create-marker/{id}', 'ArticleController@createMarker');
		Route::get('/mayoristas/articles/delete-marker/{id}', 'ArticleController@deleteMarker');
		Route::delete('/mayoristas/articles/delete-offer/{id}', 'ArticleController@deleteOffer');

		// Marcadores
		Route::post('/mayoristas/markers', 'MarkerController@store');
		Route::delete('/mayoristas/markers/{id}', 'MarkerController@delete');
		Route::get('/mayoristas/marker-groups', 'MarkerGroupController@index');
		Route::post('/mayoristas/marker-groups', 'MarkerGroupController@store');
		Route::delete('/mayoristas/marker-groups/{id}', 'MarkerGroupController@delete');
		Route::get('/mayoristas/articles/with-marker/{id}', 'ArticleController@withMarker');
		Route::get('/mayoristas/marker-groups/add-marker-to-group/{marker_group_id}/{article_id}', 'MarkerGroupController@addMarkerToGroup');

		// Exportar
		Route::get('/mayoristas/pdf/{columns}/{articles_ids}/{orientation}/{header?}', 'PdfController@articles');
		Route::get('/mayoristas/articles/exel', 'ArticleController@export')->name('provider.exel');

	// Vender
		Route::post('/mayoristas/sales', 'SaleController@store');
		Route::get('/mayoristas/articles/get-by-name/{name}', 'ArticleController@getByName');
		Route::get('/mayoristas/sales/cliente/{company_name}/{borders}/{sale_id}', 'PdfController@sale_client');
		Route::get('/mayoristas/sales/comercio/{company_name}/{borders}/{sale_id}', 'PdfController@sale_commerce');
		Route::get('/mayoristas/articles/markers', 'MarkerController@index');
		Route::get('/mayoristas/articles/marker-groups', 'MarkerGroupController@index');
		Route::get('/mayoristas/articles/availables', 'ArticleController@getAvailables');

	
		// Clientes
		Route::get('mayoristas/clients', 'ClientController@index');
		Route::post('mayoristas/clients', 'ClientController@store');
		Route::delete('mayoristas/clients/{id}', 'ClientController@delete');
		Route::post('/mayoristas/articles/import/exel', 'ArticleController@import');

	// Ventas
		Route::get('/mayoristas/sales', 'SaleController@index');
		Route::get('/mayoristas/sales/from-date/{from}/{to}/{last_day_inclusive}', 'SaleController@fromDate');
		Route::get('/mayoristas/sales/only-one-date/{date}', 'SaleController@onlyOneDate');
		Route::get('/mayoristas/sales/previus-next/{previus_next}/{direction?}/{only_one_date?}', 'SaleController@previusNext');
		Route::get('/mayoristas/sales/pdf/{sales_id}/{company_name}/{articles_cost}/{articles_subtotal_cost}/{articles_total_price}/{articles_total_cost}/{borders}', 'SaleController@pdf');
		Route::delete('/mayoristas/sales/{id}', 'SaleController@delete');
		Route::delete('/mayoristas/sales/delete-sales/{sales_id}', 'SaleController@deleteSales');

	// Empleados
		Route::get('/mayoristas/permissions', 'PermissionController@index');
		Route::get('/mayoristas/employees', 'UserController@getEmployees');
		Route::post('/mayoristas/employees', 'UserController@saveEmployee');
		Route::delete('/mayoristas/employees/{id}', 'UserController@deleteEmployee');

});

// Comercios
Route::group(['middleware' => ['has.role:commerce']], function () {

	// Principal
		Route::get('/comercios/vender', 'MainController@commerce_vender')->name('vender.commerce');
		Route::get('/comercios/ingresar', 'MainController@commerce_ingresar')->name('ingresar.commerce');
		Route::get('/comercios/listado', 'MainController@commerce_listado')->name('listado.commerce');
		Route::get('/comercios/ventas', 'MainController@commerce_ventas')->name('ventas.commerce');
		Route::get('/comercios/codigos-de-barra', 'MainController@codigos_de_barra')->name('bar-codes.commerce');
		Route::get('/comercios/empleados', 'MainController@commerce_empleados')->name('empleados.commerce');
		Route::get('/comercios/configuracion', 'MainController@commerce_config')->name('commerce.config');

	// Codigos de barra
		Route::get('/comercios/bar-codes', 'BarCodeController@index');
		Route::post('/comercios/bar-codes', 'BarCodeController@store');
		Route::get('/comercios/bar-codes/{bar_code}/{amount}/{size}/{text}', 'BarCodeController@store');
		Route::delete('/comercios/bar-codes/{id}', 'BarCodeController@delete');


	// Ingresar
		Route::get('/comercios/articles', 'ArticleController@index');
		Route::post('/comercios/articles', 'ArticleController@store');
		Route::get('/comercios/providers', 'ProviderController@index');
		Route::get('/comercios/providers/{provider_name}', 'ProviderController@store');
		Route::delete('/comercios/providers/{id}', 'ProviderController@delete');
		Route::get('/comercios/imprimir-precios/{articles_id}/{company_name}', 'PdfController@printTicket');
		/*
			* get-by-bar-code tambien se utiliza en la parte de vender
			* imprimir-precios tambien se usa en la parte de listado
		*/
		Route::get('/comercios/articles/get-by-bar-code/{bar_code}', 'ArticleController@getByBarCode');
		Route::get('/comercios/articles/bar-codes', 'ArticleController@getBarCodes');	
		Route::get('/comercios/articles/names', 'ArticleController@getNames');
		Route::get('/comercios/articles/previus-next/{index}', 'ArticleController@previusNext');
		Route::get('/comercios/bar-codes/generated', 'BarCodeController@generated');


	// Listado
		Route::post('/comercios/articles/filter', 'ArticleController@filter');
		Route::get('/comercios/articles/search/{query}', 'ArticleController@search');
		Route::get('/comercios/articles/pre-search/{query}', 'ArticleController@pre_search');
		Route::get('/comercios/providers', 'ProviderController@index');
		Route::put('/comercios/articles/{id}', 'ArticleController@update');
		Route::delete('/comercios/articles/{id}', 'ArticleController@destroy');
		Route::delete('/comercios/articles/delete-articles/{ids}', 'ArticleController@destroyArticles');
		Route::post('/comercios/articles/update-by-porcentage', 'ArticleController@updateByPorcentage');
		Route::post('/comercios/articles/create-offer', 'ArticleController@createOffer');
		Route::get('/comercios/articles/get-by-ids/{articles_id}', 'ArticleController@getByIds');
		Route::delete('/comercios/articles/delete-offer/{id}', 'ArticleController@deleteOffer');

		// Marcadores
		Route::post('/comercios/markers', 'MarkerController@store');
		Route::delete('/comercios/markers/{id}', 'MarkerController@delete');
		Route::get('/comercios/marker-groups', 'MarkerGroupController@index');
		Route::post('/comercios/marker-groups', 'MarkerGroupController@store');
		Route::delete('/comercios/marker-groups/{id}', 'MarkerGroupController@delete');
		Route::get('/comercios/articles/with-marker/{id}', 'ArticleController@withMarker');
		Route::get('/comercios/marker-groups/add-marker-to-group/{marker_group_id}/{article_id}', 'MarkerGroupController@addMarkerToGroup');

		// Listado exportar
		Route::get('/comercios/pdf/{orientation}/{header}/{columns}/{articles_ids}', 'PdfController@articles');
		Route::get('/comercios/articles/exel', 'ArticleController@export')->name('commerce.exel');
	
	// Vender
		Route::post('/comercios/sales', 'SaleController@store');
		Route::get('/comercios/articles/get-by-name/{name}', 'ArticleController@getByName');
		Route::get('/comercios/articles/markers', 'MarkerController@index');
		Route::get('/comercios/articles/marker-groups', 'MarkerGroupController@index');
		Route::get('/comercios/articles/availables', 'ArticleController@getAvailables');

	
	// Ventas
		Route::get('/comercios/sales', 'SaleController@index');
		Route::get('/comercios/sales/from-date/{from}/{to}/{last_day_inclusive}', 'SaleController@fromDate');
		Route::get('/comercios/sales/only-one-date/{date}', 'SaleController@onlyOneDate');
		Route::get('/comercios/sales/previus-next/{previus_next}/{direction}/{only_one_date?}', 'SaleController@previusNext');

		Route::get('/comercios/sales/pdf/{sales_id}/{company_name}/{articles_cost}/{articles_subtotal_cost}/{articles_total_price}/{articles_total_cost}/{borders}', 'SaleController@pdf');
		Route::delete('/comercios/sales/{id}', 'SaleController@delete');
		Route::delete('/comercios/sales/delete-sales/{sales_id}', 'SaleController@deleteSales');


	// Empleados
		Route::get('/comercios/permissions', 'PermissionController@index');
		Route::get('/comercios/employees', 'UserController@getEmployees');
		Route::post('/comercios/employees', 'UserController@saveEmployee');
		Route::delete('/comercios/employees/{id}', 'UserController@deleteEmployee');
});

// Comunes
// Los codigos de barra son comunes para los dos pero los pongo para cada uno por 
// las rutas de los componentes
	Route::get('empleados', 'MainController@empleados')->name('empleados');
	Route::get('/articles/exel', 'ArticleController@export')->name('exel');
	Route::post('/articles/import/exel', 'ArticleController@import');

	Route::get('pdf', 'PdfController@index');
	Route::get('bar-codes', 'BarCodeController@index');
	Route::post('bar-codes', 'BarCodeController@store');
	Route::get('bar-codes/{bar_code}/{amount}/{size}/{text}', 'BarCodeController@store');
	Route::delete('bar-codes/{id}', 'BarCodeController@delete');


	Route::get('ticket', 'PdfController@ticket');
	Route::get('/configuracion', 'MainController@provider_config')->name('config');

	// Configuracion
	Route::get('/get-company-name', 'UserController@getCompanyName');
	Route::get('/set-company-name/{company_name}', 
				'UserController@setCompanyName');
	Route::get('/get-percentage-card', 'UserController@getPercentageCard');
	Route::get('/set-percentage-card/{percetane_card}', 'UserController@setPercentageCard');
	Route::post('/user/password', 'UserController@update_password');


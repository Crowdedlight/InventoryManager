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

Route::group(['middleware' => ['web']], function () {
    Route::get('/', ['uses' => 'HomeController@index', 'as' => 'home']);
    Route::get('/login', ['uses' => 'HomeController@index', 'as' => 'login.page']);
    Route::post('/auth/login', ['uses' => 'AuthController@login', 'as' => 'auth.login']);
});

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('/events/', ['uses' => 'EventController@index', 'as' => 'event.all']);

    Route::post('/event/create', ['uses' => 'EventController@create', 'as' => 'event.create']);

    Route::get('/event/{id}/login', ['uses' => 'EventController@login', 'as' => 'event.login']);
    Route::get('/event/overview', ['uses' => 'EventController@overview', 'as' => 'event.overview']);

    Route::post('/event/{id}/close', ['uses' => 'EventController@close', 'as' => 'event.close']);
    Route::post('/event/{id}/add', ['uses' => 'EventController@addStorage', 'as' => 'event.add_storage']);
    Route::get('/event/logout', ['uses' => 'EventController@logout', 'as' => 'event.logout']);
    Route::post('/event/{id}/close', ['uses' => 'EventController@close', 'as' => 'events.close_event']);

    Route::get('/event/products', ['uses' => 'ProductController@index', 'as' => 'event.products']);
    Route::post('/event/{eventID}/product/add', ['uses' => 'ProductController@add', 'as' => 'products.add']);
    Route::post('/event/{eventID}/product/import', ['uses' => 'ProductController@import', 'as' => 'products.import']);
    Route::post('/event/product/{id}/delete', ['uses' => 'ProductController@delete', 'as' => 'products.delete_product']);

    Route::get('/event/storages', ['uses' => 'StorageController@index', 'as' => 'event.storages']);
    Route::post('/event/{eventID}/storage/add', ['uses' => 'StorageController@add', 'as' => 'storages.add']);
    Route::post('/event/storage/{id}/delete', ['uses' => 'StorageController@delete', 'as' => 'storages.delete_storage']);
    Route::post('/event/{id}/storage/stock', ['uses' => 'StorageController@StockStorage', 'as' => 'storages.stock_storage']);
    Route::post('/event/{id}/storage/move', ['uses' => 'StorageController@MoveProduct', 'as' => 'storages.move_product']);
    Route::post('/event/{id}/storage/updatesales', ['uses' => 'StorageController@UpdateSales', 'as' => 'storages.update_sales']);
    Route::post('/event/{id}/storage/importsales', ['uses' => 'StorageController@ImportSales', 'as' => 'storages.import_sales']);

    Route::get('/auth/logout', ['uses' => 'AuthController@logout', 'as' => 'auth.logout']);

    Route::get('izettle/auth', ['uses' => 'AuthController@AuthIzettle', 'as' => 'auth.izettleAuth']);

    Route::get('izettle/getproducts', ['uses' => 'IZettleController@GetProducts', 'as' => 'izettle.getproducts']);

    Route::get('izettle/getsales', ['uses' => 'IZettleController@getLatestSales', 'as' => 'debug.retriveSales']);

});

Route::group(['middleware' => ['web', 'admin']], function () {
    Route::get('/admin',                    ['uses' => 'AdminController@index', 'as'        => 'admin.index']);
    Route::post('/admin/api/users',         ['uses' => 'AdminController@ajaxUsers', 'as'    => 'admin.api_users']);
    Route::get('/admin/user/info/{id}',     ['uses' => 'AdminController@userInfo', 'as'     => 'admin.user_info']);
    Route::post('/admin/user/promote/{id}', ['uses' => 'AdminController@promoteUser', 'as'  => 'admin.promote_user']);
    Route::post('/admin/user/demote/{id}',  ['uses' => 'AdminController@demoteUser', 'as'   => 'admin.demote_user']);
    Route::post('/admin/user/add',          ['uses' => 'AdminController@AddUser', 'as'      => 'admin.add_user']);
    Route::post('/admin/user/delete/{id}',  ['uses' => 'AdminController@DeleteUser', 'as'   => 'admin.delete_user']);
});

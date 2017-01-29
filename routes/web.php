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
    Route::post('/event/product/{id}/delete', ['uses' => 'ProductController@delete', 'as' => 'product.delete_product']);

    Route::get('/event/products', ['uses' => 'ProductController@index', 'as' => 'event.products']);
    Route::post('/event/{eventID}/product/add', ['uses' => 'ProductController@addProduct', 'as' => 'product.add']);

    Route::get('/auth/logout', ['uses' => 'AuthController@logout', 'as' => 'auth.logout']);
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

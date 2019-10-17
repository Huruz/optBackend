<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/greeting', function (Response $response) {
    return 'Hello World!';
});

Route::post('products', "ProductController@store");

Route::get('products', "ProductController@index");

Route::get('products/{id}', "ProductController@show")->where(['id' => '[0-9]+'])->name('api.get.product');

Route::put('products/{id}', "ProductController@update")->where(['id' => '[0-9]+']);

Route::delete('products/{id}', "ProductController@destroy")->where(['id' => '[0-9]+']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', 'Api\AuthController@authenticate');

//Route::get('/tickets', 'Api\TicketController@index');

Route::get('/sellers', 'Api\SellerController@index');
Route::get('/sellers/{id}', 'Api\SellerController@seller');

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Api\AuthController@login');
    Route::post('signup', 'Api\AuthController@signUp');

    Route::group([
        'middleware' => 'auth:api'
    ], function() {
        Route::get('user', 'Api\AuthController@user');
    });
});

Route::group([
    'middleware' => 'auth:api'
], function() {
    //Sales Limit
    Route::get('sales-limit', 'Api\SalesLimitController@show');
    //register tickets
    Route::post('/tickets', 'Api\TicketController@store');
    //delete tickets
    Route::post('/tickets/{id}/delete', 'Api\TicketController@delete');
    //earnings
    Route::get('earnings', 'Api\UserController@earning');
    Route::get('winners', 'Api\UserController@winners');
});


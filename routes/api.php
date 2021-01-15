<?php

use Illuminate\Support\Facades\Route;

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
    //list sold tickets
    Route::get('/sold-tickets', 'Api\TicketController@index');
    //register tickets
    Route::post('/tickets', 'Api\TicketController@store');
    //delete tickets
    Route::post('/tickets/{id}/delete', 'Api\TicketController@delete');
    //earnings
    Route::get('earnings', 'Api\UserController@earning');
    Route::get('winners', 'Api\UserController@winners');
    Route::get('paid/{id}', 'Api\WinnerController@paid');
});


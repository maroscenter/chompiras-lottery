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
    // Sales Limits
    Route::get('sales-limit', 'Api\SalesLimitController@show');

    // Sold tickets list
    Route::get('/tickets', 'Api\TicketController@index');
    // Register a ticket
    Route::post('/tickets', 'Api\TicketController@store');
    // Show a ticket
    Route::get('/tickets/{ticket}', 'Api\TicketController@show');

    // Delete a ticket by id
    Route::post('/tickets/{id}/delete', 'Api\TicketController@delete');

    // Earnings
    Route::get('earnings', 'Api\UserController@earning');
    Route::get('winners', 'Api\UserController@winners');
    Route::get('paid/{id}', 'Api\WinnerController@paid');

    // Lotteries
    Route::get('lotteries', 'Api\LotteryController@index');

    //show detail balance
    Route::get('movement_histories', 'Api\BalanceController@index');
    //register balance
    Route::post('balance/{userId}', 'Api\BalanceController@update');
});


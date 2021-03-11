<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

// Dashboard
Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth', 'namespace' => 'Seller'], function () {
    //tickets
    Route::group(['prefix' => 'tickets'], function () {
        Route::get('create', 'TicketController@create');
        Route::post('create', 'TicketController@store');
        Route::get('{id}/delete', 'TicketController@delete');
    });
    //winners
    Route::get('winners', 'WinnerController@index');
});
Route::group(['middleware' => ['auth', 'admin'], 'namespace' => 'Admin'], function () {
    //users
    Route::group(['prefix' => 'users'], function () {
        Route::get('', 'UserController@index');
        Route::get('create', 'UserController@create');
        Route::post('create', 'UserController@store');
        Route::get('{id}/edit', 'UserController@edit');
        Route::post('{id}/edit', 'UserController@update');
        Route::get('{id}/deactivate', 'UserController@deactivate');
        Route::get('{id}/activate', 'UserController@activate');
        Route::get('{id}/balance', 'UserController@showBalance');
    });
    //lotteries
    Route::group(['prefix' => 'lotteries'], function () {
        Route::get('', 'LotteryController@index');
        Route::get('create', 'LotteryController@create');
        Route::post('create', 'LotteryController@store');
        Route::get('{id}/edit', 'LotteryController@edit');
        Route::post('{id}/edit', 'LotteryController@update');
    });
    //sales limit
    Route::get('sales-limit', 'SalesLimitController@index');
    Route::post('sales-limit', 'SalesLimitController@update');
    //raffles
    Route::group(['prefix' => 'raffles'], function () {
        Route::get('', 'RaffleController@index');
        Route::get('create', 'RaffleController@create');
        Route::post('create', 'RaffleController@store');
        Route::get('{id}', 'RaffleController@show');
    });
});

Route::group(['middleware' => 'auth', 'namespace' => 'Report'], function () {
    //reports
    Route::group(['prefix' => 'report'], function () {
        Route::get('sales', 'SaleController@index');
    });
    //balance sheets
    Route::get('balance_sheets', 'BalanceSheetController@index');
});

// Dashboard links
//Route::get('/users', 'UserController@index');
//Route::get('/user/{id}/lists', 'UserController@lists');
Route::get('/dates', 'DateController@index');

// Sent list routes
Route::get('/ticket/{id}', 'TicketController@show');
Route::get('/ticket/{id}/excel', 'TicketController@excel');
Route::get('/ticket/{id}/pdf', 'TicketController@pdf');

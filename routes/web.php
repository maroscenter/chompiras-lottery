<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

// Dashboard
Route::get('/home', 'HomeController@index');

Route::group(['middleware' => 'auth', 'namespace' => 'Seller'], function () {
    //lotteries
    Route::group(['prefix' => 'lotteries'], function () {
        Route::get('', 'LotteryController@index');
        Route::get('create', 'LotteryController@create');
        Route::post('create', 'LotteryController@store');
        Route::get('{id}/edit', 'LotteryController@edit');
        Route::post('{id}/edit', 'LotteryController@update');
    });
    //tickets
    Route::group(['prefix' => 'tickets'], function () {
        Route::get('create', 'TicketController@create');
        Route::post('create', 'TicketController@store');
        Route::get('{id}/delete', 'TicketController@delete');
    });
    //winners
    Route::get('winners', 'WinnerController@index');
});
Route::group(['middleware' => 'auth'], function () {
    //sales limit
    Route::get('sales-limit', 'SalesLimitController@index');
    Route::post('sales-limit', 'SalesLimitController@update');
    //raffles
    Route::group(['prefix' => 'raffles', 'namespace' => 'Admin'], function () {
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
});

// Dashboard links
Route::get('/users', 'UserController@index');
Route::get('/user/{id}/lists', 'UserController@lists');
Route::get('/dates', 'DateController@index');

// Sent list routes
Route::get('/ticket/{id}', 'TicketController@show');
Route::get('/ticket/{id}/excel', 'TicketController@excel');
Route::get('/ticket/{id}/pdf', 'TicketController@pdf');

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

Route::group(['middleware' => 'auth'], function () {
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
});

// Dashboard links
Route::get('/users', 'UserController@index');
Route::get('/user/{id}/lists', 'UserController@lists');
Route::get('/dates', 'DateController@index');

// Sent list routes
Route::get('/ticket/{id}', 'TicketController@show');
Route::get('/ticket/{id}/excel', 'TicketController@excel');
Route::get('/ticket/{id}/pdf', 'TicketController@pdf');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

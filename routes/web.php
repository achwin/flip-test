<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/withdraw', 'WithdrawController@index')->name('withdraw.index');
Route::post('/withdraw', 'WithdrawController@store')->name('withdraw.store');
Route::get('/disburse/{transaction_id}', 'WithdrawController@get')->name('withdraw.get');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

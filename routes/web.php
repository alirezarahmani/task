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
Auth::routes(['verify' => true]);
Route::get('/wallet/add', 'WalletController@add')->name('addwallet');
Route::post('/wallet/add', 'WalletController@save')->name('addwallet1');

Route::get('wallet/{id?}/add', 'WalletController@balance')->name('addbalance1');
Route::post('wallet/{id?}/add', 'WalletController@saveBalance')->name('addbalance');

Route::get('/wallet/{id?}/subtract', 'WalletController@subtract')->name('addsubtract1');
Route::post('/wallet/{id?}/subtract', 'WalletController@saveSubtract')->name('addsubttract');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('login/google', 'Auth\LoginController@redirectToProvider');
Route::get('login/google/callback', 'Auth\LoginController@handleProviderCallback');

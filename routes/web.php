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

Route::get('/', 'IndexController@index');

Route::get('/accounts', 'AccountController@index');
Route::post('/accounts', 'AccountController@store')->name('accounts');

Route::get('/institucional', 'InstitutionalAccountController@index');
Route::post('/institucional/accounts', 'InstitutionalAccountController@store')->name('institutional.accounts');

Route::get('/local', 'LocalAccountController@index');
Route::post('/local/accounts', 'LocalAccountController@store')->name('local.accounts');

Route::post('/password/{account}', 'PasswordRequestController@store')->name('password.renew');

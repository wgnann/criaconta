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
Route::get('/accounts/{id}/activate', 'AccountController@activateAccount');
Route::get('/accounts/{id}/cancel', 'AccountController@cancelAccountRequest');
Route::get('/accounts/todo', 'AccountController@todoAccounts');

Route::get('/institucional', 'InstitutionalAccountController@index');
Route::post('/institucional/accounts', 'InstitutionalAccountController@store')->name('institutional.accounts');

Route::get('/login/senhaunica', 'Auth\LoginController@redirectToProvider')->name('login');
Route::get('/login/senhaunica/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/logout', 'Auth\LoginController@logout');

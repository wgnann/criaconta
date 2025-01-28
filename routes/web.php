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
Route::get('/accounts/{id}/delete', 'AccountController@deleteAccount');
Route::get('/accounts/{username}/info', 'AccountController@accountFromUsername');
Route::get('/accounts/todo', 'AccountController@todoAccounts');

Route::post('/password/{account}', 'PasswordRequestController@store')->name('password.renew');
Route::get('/password/{id}/reset', 'PasswordRequestController@resetPassword');
Route::get('/password/requests', 'PasswordRequestController@listRequests');

Route::get('/institucional', 'InstitutionalAccountController@index');
Route::post('/institucional/accounts', 'InstitutionalAccountController@store')->name('institutional.accounts');

Route::get('/local', 'LocalAccountController@index');
Route::post('/local/accounts', 'LocalAccountController@store')->name('local.accounts');

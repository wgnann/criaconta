<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/accounts/todo', 'API\AccountController@todoAccounts');
Route::get('/accounts/{id}/activate', 'API\AccountController@activateAccount');
Route::get('/accounts/{id}/cancel', 'API\AccountController@cancelAccountRequest');
Route::get('/accounts/{id}/delete', 'API\AccountController@deleteAccount');
Route::get('/accounts/{username}/info', 'API\AccountController@accountFromUsername');

Route::get('/password/{id}/reset', 'API\PasswordRequestController@resetPassword');
Route::get('/password/requests', 'API\PasswordRequestController@listRequests');

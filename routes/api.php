<?php

use Illuminate\Http\Request;
use App\Http\Controllers\API\AccountController;
use App\Http\Controllers\API\PasswordRequestController;

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

Route::get('/accounts/todo', [AccountController::class, 'todoAccounts']);
Route::get('/accounts/{id}/activate', [AccountController::class, 'activateAccount']);
Route::get('/accounts/{id}/cancel', [AccountController::class, 'cancelAccountRequest']);
Route::get('/accounts/{id}/delete', [AccountController::class,'deleteAccount']);
Route::get('/accounts/{username}/info', [AccountController::class, 'accountFromUsername']);

Route::get('/password/{id}/reset', [PasswordRequestController::class,'resetPassword']);
Route::get('/password/requests', [PasswordRequestController::class, 'listRequests']);

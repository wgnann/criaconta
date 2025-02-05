<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\InstitutionalAccountController;
use App\Http\Controllers\LocalAccountController;
use App\Http\Controllers\PasswordRequestController;

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

Route::get('/', [IndexController::class, 'index']);


Route::get('/accounts', [AccountController::class, 'index'])
    ->name('account');
Route::get('/accounts/show/{account}', [AccountController::class, 'show'])
    ->name('account.show');
Route::post('/accounts', [AccountController::class, 'store'])
    ->name('account.store');
Route::get('/accounts/list', [AccountController::class, 'list'])
    ->name('account.list');
Route::get('/accounts/list-admin', [AccountController::class, 'listAdmin'])
    ->name('account.listadmin');


Route::get('/institucional', [InstitutionalAccountController::class, 'index'])
    ->name('institutional.account');
Route::post('/institucional/accounts', [InstitutionalAccountController::class, 'store'])
    ->name('institutional.account.store');


Route::get('/local', [LocalAccountController::class, 'index'])
    ->name('local.account');
Route::post('/local/accounts', [LocalAccountController::class, 'store'])
    ->name('local.account.store');


Route::post('/password/{account}', [PasswordRequestController::class, 'store'])
    ->name('password.renew');

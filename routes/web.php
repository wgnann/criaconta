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

Route::get('/accounts', [AccountController::class, 'index']);
Route::post('/accounts', [AccountController::class, 'store'])->name('accounts');

Route::get('/institucional', [InstitutionalAccountController::class, 'index']);
Route::post('/institucional/accounts', [InstitutionalAccountController::class, 'store'])->name('institutional.accounts');

Route::get('/local', [LocalAccountController::class, 'index']);
Route::post('/local/accounts', [LocalAccountController::class, 'store'])->name('local.accounts');

Route::post('/password/{account}', [PasswordRequestController::class, 'store'])->name('password.renew');

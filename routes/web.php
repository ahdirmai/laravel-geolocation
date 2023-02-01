<?php

use App\Http\Controllers\admin\UnitsController;
use App\Http\Controllers\admin\UsersController;
use App\Http\Controllers\user\DashboardController;
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

Route::resource('user', UsersController::class);
Route::resource('unit', UnitsController::class);
Auth::routes();

Route::get('/home', [DashboardController::class, 'index'])->name('home');

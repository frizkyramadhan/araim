<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DepartmentController;

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

Route::get('register', [RegisterController::class, 'index'])->name('register')->middleware('guest');
Route::post('register', [RegisterController::class, 'store']);

Route::get('login', [LoginController::class, 'index'])->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'authenticate']);
Route::post('logout', [LoginController::class, 'logout']);

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard', ['title' => 'Dashboard']);
    });
    Route::get('contact', function () {
        return view('contact', ['title' => 'Contact Us']);
    });
});

Route::group(['middleware' => 'check_role:admin,superuser' ], function() {
    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('projects', ProjectController::class)->except(['show']);
});

Route::middleware('check_role:admin')->group(function () {
    Route::resource('users', UserController::class)->except(['show']);
});
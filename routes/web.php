<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BastController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\InventoryController;
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
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('positions', PositionController::class)->except(['show']);
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('assets', AssetController::class)->except(['show']);

    Route::get('employees/getEmployees', [EmployeeController::class, 'getEmployees'])->name('employees.getEmployees');
    Route::get('employees/json', [EmployeeController::class, 'json'])->name('employees.json');
    Route::resource('employees', EmployeeController::class);
    
    Route::get('inventories/getInventories', [InventoryController::class, 'getInventories'])->name('inventories.getInventories');
    Route::get('inventories/json', [InventoryController::class, 'json'])->name('inventories.json');
    Route::resource('inventories', InventoryController::class);
});

Route::middleware('check_role:admin')->group(function () {
    Route::resource('components', ComponentController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::resource('basts', BastController::class);
});
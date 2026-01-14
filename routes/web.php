<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BapbController;
use App\Http\Controllers\BastController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\DashboardController;
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

Route::get('inventories/qrcodeJson/{id}', [InventoryController::class, 'qrcodeJson'])->name('inventories.qrcodeJson');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('dashboard/summary/{id}', [DashboardController::class, 'summary'])->name('dashboard.summary');
    Route::get('dashboard/logs', [DashboardController::class, 'logs'])->name('dashboard.logs');
    Route::get('dashboard/json', [DashboardController::class, 'json'])->name('dashboard.json');
    Route::get('dashboard/getLogs', [DashboardController::class, 'getLogs'])->name('dashboard.getLogs');
    Route::get('dashboard/getInventoriesWithoutBast', [DashboardController::class, 'getInventoriesWithoutBast'])->name('dashboard.getInventoriesWithoutBast');
    Route::get('dashboard/getInventoriesWithoutBapb', [DashboardController::class, 'getInventoriesWithoutBapb'])->name('dashboard.getInventoriesWithoutBapb');
    Route::get('dashboard/getBastsWithoutSignedDocument', [DashboardController::class, 'getBastsWithoutSignedDocument'])->name('dashboard.getBastsWithoutSignedDocument');
    Route::get('contact', function () {
        return view('contact', ['title' => 'Contact Us']);
    });
    Route::post('logout', [LoginController::class, 'logout']);

    Route::get('inventories/getInventories', [InventoryController::class, 'getInventories'])->name('inventories.getInventories');
    Route::get('inventories/json', [InventoryController::class, 'json'])->name('inventories.json');
    Route::get('inventories', [InventoryController::class, 'index'])->name('inventories.index');
    Route::get('inventories/export', [InventoryController::class, 'export'])->name('inventories.export');
    Route::get('inventories/import', [InventoryController::class, 'import'])->name('inventories.import');
    Route::get('inventories/create', [InventoryController::class, 'create'])->name('inventories.create');
    Route::get('inventories/create/{employee_id}', [InventoryController::class, 'create'])->name('inventories.create');
    Route::get('inventories/{inventory}', [InventoryController::class, 'show'])->name('inventories.show');
    Route::get('trackings', [TrackingController::class, 'index'])->name('trackings.index');

    // role admin & superuser
    Route::resource('departments', DepartmentController::class)->except(['show']);
    Route::resource('categories', CategoryController::class)->except(['show']);
    Route::resource('positions', PositionController::class)->except(['show']);
    Route::resource('projects', ProjectController::class)->except(['show']);
    Route::resource('assets', AssetController::class)->except(['show']);

    Route::post('locations/storeFromInventory', [LocationController::class, 'storeFromInventory'])->name('locations.storeFromInventory');
    Route::resource('locations', LocationController::class)->except(['show']);

    Route::post('brands/storeFromInventory', [BrandController::class, 'storeFromInventory'])->name('brands.storeFromInventory');
    Route::resource('brands', BrandController::class)->except(['show']);

    Route::get('employees/getEmployees', [EmployeeController::class, 'getEmployees'])->name('employees.getEmployees');
    Route::get('employees/json', [EmployeeController::class, 'json'])->name('employees.json');
    Route::resource('employees', EmployeeController::class);

    Route::get('inventories/edit/{inventory}', [InventoryController::class, 'edit'])->name('inventories.edit');
    Route::delete('inventories/{inventory}', [InventoryController::class, 'destroy'])->name('inventories.destroy');

    Route::post('inventories', [InventoryController::class, 'store'])->name('inventories.store');
    Route::post('inventories/importProcess', [InventoryController::class, 'importProcess'])->name('inventories.importProcess');
    Route::get('inventories/transfer/{id}', [InventoryController::class, 'transfer'])->name('inventories.transfer');
    Route::patch('inventories/transferProcess/{id}', [InventoryController::class, 'transferProcess'])->name('inventories.transferProcess');
    Route::get('inventories/qrcode/{id}', [InventoryController::class, 'qrcode'])->name('inventories.qrcode');
    Route::get('inventories/delete_qrcode/{id}', [InventoryController::class, 'delete_qrcode'])->name('inventories.delete_qrcode');
    Route::get('inventories/deleteImage/{id}', [InventoryController::class, 'deleteImage'])->name('inventories.deleteImage');
    Route::get('inventories/deleteImages/{inventory_no}', [InventoryController::class, 'deleteImages'])->name('inventories.deleteImages');
    Route::get('inventories/print_qrcode/{id}', [InventoryController::class, 'print_qrcode'])->name('inventories.print_qrcode');
    Route::get('inventories/print_qrcode_employee/{id}', [InventoryController::class, 'print_qrcode_employee'])->name('inventories.print_qrcode_employee');
    Route::get('inventories/{inventory}/{employee_id}', [InventoryController::class, 'show'])->name('inventories.show');
    Route::get('inventories/edit/{inventory}/{id}', [InventoryController::class, 'edit'])->name('inventories.edit');
    Route::patch('inventories/{inventory}', [InventoryController::class, 'update'])->name('inventories.update');
    Route::delete('inventories/{inventory}/{id}', [InventoryController::class, 'destroy'])->name('inventories.destroy');

    Route::post('inventories/addImages/{id}', [InventoryController::class, 'addImages'])->name('inventories.addImages');


    // role admin only
    Route::resource('components', ComponentController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);

    Route::get('basts', [BastController::class, 'index'])->name('basts.index');
    Route::get('basts/getInventories', [BastController::class, 'getInventories'])->name('basts.getInventories');
    Route::get('basts/create', [BastController::class, 'create'])->name('basts.create');
    Route::post('basts', [BastController::class, 'store'])->name('basts.store');
    Route::get('basts/{bast_no}', [BastController::class, 'show'])->name('basts.show');
    Route::get('basts/{bast_no}/edit', [BastController::class, 'edit'])->name('basts.edit');
    Route::patch('basts/{bast_no}', [BastController::class, 'update'])->name('basts.update');
    Route::get('basts/delete_item/{id}', [BastController::class, 'delete_item'])->name('basts.delete_item');
    Route::delete('basts/{bast_no}', [BastController::class, 'destroy'])->name('basts.destroy');
    Route::get('basts/{bast_no}/print', [BastController::class, 'print'])->name('basts.print');
    Route::post('basts/{bast_no}/upload-document', [BastController::class, 'uploadDocument'])->name('basts.uploadDocument');
    Route::delete('basts/{bast_no}/delete-document', [BastController::class, 'deleteDocument'])->name('basts.deleteDocument');
    Route::post('basts/{bast_no}/send-email', [BastController::class, 'sendEmail'])->name('basts.sendEmail');
    Route::get('basts/{bast_no}/preview-email', [BastController::class, 'previewEmail'])->name('basts.previewEmail');

    Route::get('bapbs', [BapbController::class, 'index'])->name('bapbs.index');
    Route::get('bapbs/getInventories', [BapbController::class, 'getInventories'])->name('bapbs.getInventories');
    Route::get('bapbs/create', [BapbController::class, 'create'])->name('bapbs.create');
    Route::post('bapbs', [BapbController::class, 'store'])->name('bapbs.store');
    Route::get('bapbs/{bapb_no}', [BapbController::class, 'show'])->name('bapbs.show');
    Route::get('bapbs/{bapb_no}/edit', [BapbController::class, 'edit'])->name('bapbs.edit');
    Route::patch('bapbs/{bapb_no}', [BapbController::class, 'update'])->name('bapbs.update');
    Route::get('bapbs/delete_item/{id}', [BapbController::class, 'delete_item'])->name('bapbs.delete_item');
    Route::delete('bapbs/{bapb_no}', [BapbController::class, 'destroy'])->name('bapbs.destroy');
    Route::get('bapbs/{bapb_no}/print', [BapbController::class, 'print'])->name('bapbs.print');
    Route::post('bapbs/{bapb_no}/upload-document', [BapbController::class, 'uploadDocument'])->name('bapbs.uploadDocument');
    Route::delete('bapbs/{bapb_no}/delete-document', [BapbController::class, 'deleteDocument'])->name('bapbs.deleteDocument');
    Route::post('bapbs/{bapb_no}/send-email', [BapbController::class, 'sendEmail'])->name('bapbs.sendEmail');
    Route::get('bapbs/{bapb_no}/preview-email', [BapbController::class, 'previewEmail'])->name('bapbs.previewEmail');
});

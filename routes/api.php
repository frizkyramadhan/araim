<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\InventoryApiController;
use App\Http\Controllers\Api\HeroHoldingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('api.key')->get(
    'hero/employees/{heroEmployeeId}/inventories',
    [HeroHoldingController::class, 'show']
);
Route::middleware('api.key')->get(
    'hero/employees/{heroEmployeeId}/holdings',
    [HeroHoldingController::class, 'show']
);

Route::get('inventories/qrcode_json/{id}', [InventoryApiController::class, 'qrcode_json'])->name('inventories.qrcode_json');

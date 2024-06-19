<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UserController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\VehicleOwnerHistoryController;
use App\Http\Controllers\ImportExcelController;
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

Route::get('/test', function() {
    return 'TEST';
});

Route::apiResource('users', UserController::class);
Route::apiResource('vehicles', VehicleController::class);

Route::post('users/{id}/restore', [UserController::class, 'restore']);
Route::post('vehicles/{id}/restore', [VehicleController::class, 'restore']);

Route::post('/import/excel', [ImportExcelController::class, 'importFromExcel']);
Route::get('/history', [VehicleOwnerHistoryController::class, 'getAllOwnersHistory']);
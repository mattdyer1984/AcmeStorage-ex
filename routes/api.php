<?php

use App\Http\Controllers\ItemController;
use App\Http\Controllers\UnitController;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/units', [UnitController::class, 'addUnit']);
Route::post('/items', [ItemController::class, 'addItem']);
Route::get('/items', [ItemController::class, 'getAllItems']);
Route::get('/units', [UnitController::class, 'getAllUnits']);
Route::get('/units', [UnitController::class, 'getAvailableUnits']);
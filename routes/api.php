<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LineBotController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees', [EmployeeController::class, 'create']);
Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
Route::post('/employees/reorder', [EmployeeController::class, 'reorder']);
Route::post('/employees/set-date', [EmployeeController::class, 'setDate']);

Route::post('/line-bot', [LineBotController::class, 'onEvent']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

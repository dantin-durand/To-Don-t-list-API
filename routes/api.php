<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\TaskController;
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



Route::post('auth/register', [ApiTokenController::class, 'register']);
Route::post('auth/login', [ApiTokenController::class, 'login']);
Route::middleware('auth:sanctum')->post('auth/me', [ApiTokenController::class, 'me']);
Route::middleware('auth:sanctum')->post('auth/logout', [ApiTokenController::class, 'logout']);

Route::middleware('auth:sanctum')->get('tasks', [TaskController::class, 'getAll']);
Route::middleware('auth:sanctum')->get('tasks/{id}', [TaskController::class, 'getOne']);
Route::middleware('auth:sanctum')->post('tasks', [TaskController::class, 'create']);
Route::middleware('auth:sanctum')->put('tasks/{id}', [TaskController::class, 'update']);
Route::middleware('auth:sanctum')->delete('tasks/{id}', [TaskController::class, 'destroy']);

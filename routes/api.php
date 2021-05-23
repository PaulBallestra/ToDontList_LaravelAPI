<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\TaskController;



//Auth register
Route::post('auth/register', [ApiTokenController::class, 'register']);
Route::post('auth/login', [ApiTokenController::class, 'login']);

//Account
Route::middleware('auth:sanctum')->post('auth/me', [ApiTokenController::class, 'me']);
Route::middleware('auth:sanctum')->post('auth/logout', [ApiTokenController::class, 'logout']);

//Tasks
Route::middleware('auth:sanctum')->post('tasks', [TaskController::class, 'create']);
Route::middleware('auth:sanctum')->get('tasks', [TaskController::class, 'showAll']);
Route::middleware('auth:sanctum')->get('tasks/{id}', [TaskController::class, 'show']);
Route::middleware('auth:sanctum')->delete('tasks/{id}', [TaskController::class, 'delete']);

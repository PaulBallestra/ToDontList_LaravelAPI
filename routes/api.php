<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//Auth register
Route::post('auth/register', [\App\Http\Controllers\ApiTokenController::class, 'register']);
Route::post('auth/login', [\App\Http\Controllers\ApiTokenController::class, 'login']);

//Account
Route::middleware('auth:sanctum')->post('auth/me', [App\Http\Controllers\ApiTokenController::class, 'me']);

Route::middleware('auth:api')->post('auth/me', function (Request $request) {
    return $request->user();
});

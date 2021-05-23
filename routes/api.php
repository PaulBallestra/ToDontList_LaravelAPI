<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



//Auth register
Route::post('auth/register', [\App\Http\Controllers\ApiTokenController::class, 'register']);

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

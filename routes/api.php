<?php

use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/services', [ApiController::class, 'services']);
    Route::post('/logout', [ApiController::class, 'logout']);
});
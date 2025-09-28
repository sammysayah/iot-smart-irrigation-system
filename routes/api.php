<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\IrrigationApiController;

Route::prefix('irrigation')->group(function () {
    Route::post('/sensor-data', [IrrigationApiController::class, 'receiveData']);
    Route::get('/system-status', [IrrigationApiController::class, 'getSystemStatus']);
    Route::post('/manual-water', [IrrigationApiController::class, 'manualWater']);
});
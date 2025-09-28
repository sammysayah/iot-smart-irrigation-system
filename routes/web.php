<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Irrigation system routes - FIXED
    Route::post('/settings/update', [DashboardController::class, 'updateSettings'])->name('settings.update');
    Route::post('/water/manual', [DashboardController::class, 'manualWater'])->name('water.manual');
    Route::get('/sensor/latest', [DashboardController::class, 'latestSensorData'])->name('sensor.latest');
    
});

require __DIR__.'/auth.php';
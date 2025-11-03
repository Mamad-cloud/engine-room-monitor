<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::get('/', [DeviceController::class, 'index'])->name('devices.index');
Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');

Route::post('/devices/{device}/commands', [DeviceController::class, 'sendCommand'])->name('devices.commands');

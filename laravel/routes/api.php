<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/hello', function(Request $req) {
    return response()->json(['hi']);
});

Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
Route::post('/devices/{device}/commands', [DeviceController::class, 'sendCommand'])->name('devices.commands');

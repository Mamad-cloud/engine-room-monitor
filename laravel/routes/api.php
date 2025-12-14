<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\SubscriptionApiController;
use App\Http\Controllers\Api\EngineRoomApiController;
use App\Http\Controllers\Api\PeripheralModeController;
use App\Http\Controllers\Api\PeripheralController;
use App\Http\Controllers\Api\AdminConfigApiController;




Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/hello', function(Request $req) {
    return response()->json(['hi']);
});


Route::post('/users', [UserApiController::class, 'store'])->name('api.users.store');
Route::put('/users/{user}', [UserApiController::class, 'update'])->name('api.users.update');
Route::delete('/users/{user}', [UserApiController::class, 'destroy'])->name('api.users.destroy');



Route::post('/subscriptions', [SubscriptionApiController::class, 'store'])->name('api.subscriptions.store');
Route::put('/subscriptions/{subscription}', [SubscriptionApiController::class, 'update'])->name('api.subscriptions.update');
Route::delete('/subscriptions/{subscription}', [SubscriptionApiController::class, 'destroy'])->name('api.subscriptions.destroy');




Route::post('/engine-rooms', [EngineRoomApiController::class, 'store'])->name('api.engine-rooms.store');
Route::put('/engine-rooms/{engineRoom}', [EngineRoomApiController::class, 'update'])->name('api.engine-rooms.update');
Route::delete('/engine-rooms/{engineRoom}', [EngineRoomApiController::class, 'destroy'])->name('api.engine-rooms.destroy');

Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');
Route::post('/devices/{device}/commands', [DeviceController::class, 'sendCommand'])->name('devices.commands');

Route::post('/admin/event-types', [AdminConfigApiController::class, 'storeEventType']);
Route::post('/admin/peripheral-modes', [AdminConfigApiController::class, 'storePeripheralMode']);

Route::get('/peripheral-modes', [PeripheralModeController::class, 'index']);
Route::post('/peripheral-modes', [PeripheralModeController::class, 'store']);
Route::put('/peripheral-modes/{id}', [PeripheralModeController::class, 'update']);
Route::delete('/peripheral-modes/{id}', [PeripheralModeController::class, 'destroy']);

// Peripherals (optionally namespaced under devices)
Route::get('/devices/{device_id}/peripherals', [PeripheralController::class, 'index']);
Route::post('/devices/{device_id}/peripherals', [PeripheralController::class, 'store']);
Route::post('/peripherals', [PeripheralController::class, 'store']); // manual create without device path
Route::delete('/peripherals/{id}', [PeripheralController::class, 'destroy']);

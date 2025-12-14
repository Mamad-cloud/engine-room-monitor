<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\EngineRoomController;
use App\Http\Controllers\AdminConfigController;


Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');

Route::get('/', function () {
    return view('pages.dashboard');
})->name('mainpage');


Route::get('/liveMonitoring', function () {
    return view('pages.live_manitoring');
})->name('liveMonitoring');


Route::get('/equipment', function () {
    return view('pages.equipment');
})->name('equipment');



Route::get('/maintenance', function () {
    return view('pages.maintenance_and_repair');
})->name('maintenance');


Route::get('/notifaction', function () {
    return view('pages.notifaction');
})->name('notifaction');


Route::get('/reports', function () {
    return view('pages.records_and_reports');
})->name('reports');


Route::get('/user', function () {
    return view('pages.user');
})->name('user');



Route::get('/setting', function () {
    return view('pages.setting');
})->name('setting');


Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
Route::get('/engine-rooms', [EngineRoomController::class, 'index'])->name('engine-rooms.index');



Route::get('/event-types', [AdminConfigController::class, 'eventTypes'])
    ->name('event-types');
    
Route::get('/peripheral-modes', [AdminConfigController::class, 'peripheralModes'])
    ->name('peripheral-modes');
    
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;

Route::get('/devices', [DeviceController::class, 'index'])->name('devices.index');
// Route::post('/devices', [DeviceController::class, 'store'])->name('devices.store');

// Route::post('/devices/{device}/commands', [DeviceController::class, 'sendCommand'])->name('devices.commands');


// صفحه اصلی 
Route::get('/', function () {
    return view('pages.dashboard');
})->name('mainpage');

// مانیتورینگ زنده
Route::get('/liveMonitoring', function () {
    return view('pages.live_manitoring');
})->name('liveMonitoring');

// صفحه تجهیزات
Route::get('/equipment', function () {
    return view('pages.equipment');
})->name('equipment');


// صفحه تعمیرات و نگهداری
Route::get('/maintenance', function () {
    return view('pages.maintenance_and_repair');
})->name('maintenance');

// صفحه هشدار و اعلان ها
Route::get('/notifaction', function () {
    return view('pages.notifaction');
})->name('notifaction');

// صفحه سوابق و گزارشات 
Route::get('/reports', function () {
    return view('pages.records_and_reports');
})->name('reports');

// صفحه کاربران 
Route::get('/user', function () {
    return view('pages.user');
})->name('user');

// صفحه تنظیمات 
Route::get('/setting', function () {
    return view('pages.setting');
})->name('setting');
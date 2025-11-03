<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

Route::post('/devices/{id}/commands', function (Request $req, $id) {
    $body = $req->all();
    $cmd = array_merge(['device_id' => $id, 'req_id' => uniqid()], $body);
    Redis::publish('mcu.commands', json_encode($cmd));
    return response()->json(['ok' => true, 'published' => $cmd]);
});

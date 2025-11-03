<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Device;
use App\Events\CommandSent;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function index()
    {
        // Load devices from mongodb
        $devices = Device::orderBy('_id', 'desc')->get();
        return view('devices.index', compact('devices'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'nullable|string|max:255',
        ]);


        $device = new Device();
        $device->name = $data['name'];
        if (!empty($data['device_id'])) {
            $device->device_id = $data['device_id'];
        }
        $device->save();

        // Optionally broadcast that a new device exists
        broadcast(new \App\Events\DeviceRegistered($device))->toOthers();

        // if ajax request return json
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($device, 201);
        }

        return redirect()->route('devices.index')->with('status', 'Device created');
    }

    public function sendCommand(Request $request, Device $device): JsonResponse
    {
        $data = $request->validate([
            'command' => 'required|string|max:1000',
            'meta' => 'nullable|array',
        ]);

        $cmdId = (string) Str::uuid();
        $payload = [
            'cmd_id' => $cmdId,
            'device_id' => $device->device_id,
            'command' => $data['command'],
            'meta' => $data['meta'] ?? null,
            'ts' => now()->toIso8601String(),
        ];

        // 1) Publish to Redis for node-bridge to pick up and forward to MCU
        try {
            // use Redis facade - this publishes to a standard Redis channel
            Redis::publish('mcu.commands', json_encode($payload));
        } catch (\Throwable $e) {
            // Log but still let the request respond
            \Log::error('Failed to publish command to Redis', ['err' => $e->getMessage(), 'payload' => $payload]);
            return response()->json(['error' => 'Failed to publish command'], 500);
        }

        // 2) Broadcast to the device channel so UI can update (instant feedback)
        broadcast(new CommandSent($payload))->toOthers();

        return response()->json(['ok' => true, 'cmd_id' => $cmdId]);
    }
}

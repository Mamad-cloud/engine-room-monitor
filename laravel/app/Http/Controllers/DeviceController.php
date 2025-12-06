<?php

namespace App\Http\Controllers;

use App\Models\SensorMessage;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Events\CommandSent;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        $engineRoomId = $request->query('engine_room');

        if ($engineRoomId) {
            $devices = Device::where('engine_room_id', $engineRoomId)
                             ->orderBy('_id', 'desc')
                             ->get();
        } else {
            $devices = Device::orderBy('_id', 'desc')->get();
        }

        $deviceIds = $devices->pluck('device_id')->filter()->values()->all();

        $messagesQuery = SensorMessage::orderBy('ts', 'desc');
        if (!empty($deviceIds)) {
            $messagesQuery->whereIn('device_id', $deviceIds);
        } elseif ($engineRoomId) {
            $messagesQuery->where('engine_room_id', $engineRoomId);
        }
        $messages = $messagesQuery->limit(50)->get();

        return view('devices.index', [
            'devices' => $devices,
            'messages' => $messages,
            'engine_room_id' => $engineRoomId,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'nullable|string|max:255',
            'engine_room_id' => 'nullable|string|max:64',
        ]);

        $device = new Device();
        $device->name = $data['name'];
        if (!empty($data['device_id'])) {
            $device->device_id = $data['device_id'];
        }
        if (!empty($data['engine_room_id'])) {
            $device->engine_room_id = $data['engine_room_id'];
        }
        $device->save();

        broadcast(new \App\Events\DeviceRegistered($device))->toOthers();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json($device, 201);
        }

        return redirect()->route('devices.index', ['engine_room' => $data['engine_room_id'] ?? null])->with('status', 'Device created');
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
            'engine_room_id' => $device->engine_room_id ?? null,
            'ts' => now()->toIso8601String(),
        ];

        try {
            Redis::publish('mcu.commands', json_encode($payload));
        } catch (\Throwable $e) {
            \Log::error('Failed to publish command to Redis', ['err' => $e->getMessage(), 'payload' => $payload]);
            return response()->json(['error' => 'Failed to publish command'], 500);
        }

        broadcast(new CommandSent($payload))->toOthers();

        return response()->json(['ok' => true, 'cmd_id' => $cmdId]);
    }
}

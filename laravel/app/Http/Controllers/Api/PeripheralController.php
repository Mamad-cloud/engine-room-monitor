<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Peripheral;
use App\Models\PeripheralMode;

class PeripheralController extends Controller
{
    // list peripherals for a device or engine_room
    public function index(Request $request, $deviceId = null)
    {
        $q = Peripheral::query();
        if ($deviceId) $q->where('device_id', $deviceId);
        if ($request->has('engine_room')) {
            $q->where('engine_room_id', $request->query('engine_room'));
        }
        $list = $q->orderBy('index', 'asc')->get();
        return response()->json($list);
    }

    // manual create / upsert a peripheral for a device (admin UI)
    public function store(Request $request, $deviceId = null)
    {
        $data = $request->validate([
            'device_id' => 'nullable|string',
            'mode_id' => 'required',
            'name' => 'nullable|string|max:200',
            'persian_label' => 'nullable|string|max:200',
            'index' => 'nullable|integer',
            'meta' => 'nullable|array',
            'active' => 'nullable|boolean',
            'engine_room_id' => 'nullable|string',
        ]);

        $device_id = $deviceId ?? $data['device_id'] ?? null;
        if (!$device_id) return response()->json(['error' => 'device_id required'], 422);

        // upsert by device + index if index provided; otherwise create
        if (isset($data['index'])) {
            $p = Peripheral::where('device_id', $device_id)->where('index', intval($data['index']))->first();
            if ($p) {
                $p->update(array_merge($data, ['device_id' => $device_id]));
                return response()->json($p);
            }
        }

        $payload = array_merge($data, ['device_id' => $device_id]);
        $per = Peripheral::create($payload);
        return response()->json($per, 201);
    }

    public function destroy($id)
    {
        $p = Peripheral::findOrFail($id);
        $p->delete();
        return response()->json(['ok' => true]);
    }
}

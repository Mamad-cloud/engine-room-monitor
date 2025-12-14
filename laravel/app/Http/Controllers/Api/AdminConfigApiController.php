<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\PeripheralMode;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AdminConfigApiController extends Controller
{
    public function storeEventType(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
        ]);

        $et = EventType::create([
            'name' => $data['name'],
            'code' => $data['code'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return response()->json($et, 201);
    }

    public function storePeripheralMode(Request $request)
    {
        $data = $request->validate([
            'mode' => 'required|string|max:255',
            'legacy_id' => 'nullable|integer',
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:2000',
        ]);

        // prevent duplicate legacy_id if provided
        if (!empty($data['legacy_id'])) {
            $exists = PeripheralMode::where('legacy_id', intval($data['legacy_id']))->first();
            if ($exists) {
                return response()->json(['error' => 'legacy_id already exists'], 422);
            }
        }

        $pm = PeripheralMode::create([
            'mode' => $data['mode'],
            'slug' => Str::slug($data['mode']),
            'legacy_id' => $data['legacy_id'] ?? null,
            'unit' => $data['unit'] ?? null,
            'description' => $data['description'] ?? null,
        ]);

        return response()->json($pm, 201);
    }
}

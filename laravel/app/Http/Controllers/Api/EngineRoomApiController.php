<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EngineRoom;
use App\Models\Subscription;

class EngineRoomApiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'subscription_id' => 'required',
            'name' => 'required|string|max:191',
            'location' => 'nullable|string|max:255',
            'meta' => 'nullable|array',
        ]);

        // ensure subscription exists
        $sub = Subscription::find($data['subscription_id']);
        if (!$sub) {
            return response()->json(['result' => false, 'message' => 'Subscription not found'], 422);
        }

        $engineRoom = EngineRoom::create([
            'subscription_id' => $sub->id,
            // still store typo field if user provided it
            'subscribtion_id' => $data['subscribtion_id'] ?? $sub->id,
            'name' => $data['name'],
            'location' => $data['location'] ?? null,
            'meta' => $data['meta'] ?? [],
        ]);

        return response()->json(['result' => true, 'engine_room' => $engineRoom], 201);
    }

    public function update(Request $request, EngineRoom $engineRoom)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:191',
            'location' => 'sometimes|nullable|string|max:255',
            'meta' => 'sometimes|nullable|array',
        ]);

        $engineRoom->update($data);

        return response()->json(['result' => true, 'engine_room' => $engineRoom]);
    }

    public function destroy(EngineRoom $engineRoom)
    {
        $engineRoom->delete();
        return response()->json(['result' => true]);
    }
}

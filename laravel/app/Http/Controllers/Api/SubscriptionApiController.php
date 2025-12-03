<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Str;

class SubscriptionApiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required',
            'title' => 'required|string|max:191',
            'status' => 'nullable|string|in:active,inactive',
            'meta' => 'nullable|array',
        ]);

        // ensure user exists
        $user = User::find($data['user_id']);
        if (!$user) {
            return response()->json(['result' => false, 'message' => 'user not found'], 422);
        }

        $sub = Subscription::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'status' => $data['status'] ?? 'active',
            'meta' => $data['meta'] ?? [],
            'uuid' => Str::uuid()->toString(),
        ]);

        return response()->json(['result' => true, 'subscription' => $sub], 201);
    }

    public function update(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'title' => 'sometimes|required|string|max:191',
            'status' => 'sometimes|nullable|string|in:active,inactive',
            'meta' => 'sometimes|nullable|array',
        ]);

        $subscription->update($data);

        return response()->json(['result' => true, 'subscription' => $subscription]);
    }

    public function destroy(Subscription $subscription)
    {
        $subscription->delete();
        return response()->json(['result' => true]);
    }
}

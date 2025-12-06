<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EngineRoom;
use App\Models\Subscription;
use App\Models\User;

class EngineRoomController extends Controller
{
    public function index(Request $request)
    {
        $subscriptionId = $request->query('subscription');
        $q = (string) $request->query('q', '');
        $perPage = 12;

        $subscription = null;
        $engineRooms = EngineRoom::query();

        if ($subscriptionId) {
            $subscription = Subscription::find($subscriptionId);
            if ($subscription) {
                // consider both fields stored on engine room
                $engineRooms->where(function ($qBuilder) use ($subscription) {
                    $qBuilder->where('subscription_id', $subscription->id)
                             ->orWhere('subscribtion_id', $subscription->id);
                });
            }
        }

        if ($q) {
            $engineRooms->where('name', 'like', "%{$q}%");
        }

        $engineRooms = $engineRooms->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('engine_rooms.index', compact('subscription', 'engineRooms', 'q'));
    }
}

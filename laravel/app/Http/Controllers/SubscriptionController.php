<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->query('user');
        $q = (string) $request->query('q', '');
        $perPage = 12;

        $user = null;
        $subscriptions = Subscription::query();

        if ($userId) {
            $user = User::find($userId);
            if ($user) {
                $subscriptions->where('user_id', $user->id);
            }
        }

        if ($q) {
            $subscriptions->where('title', 'like', "%{$q}%");
        }

        $subscriptions = $subscriptions->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        return view('subscriptions.index', compact('user', 'subscriptions', 'q'));
    }
}

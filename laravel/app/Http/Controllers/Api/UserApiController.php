<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Str;

class UserApiController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'nullable|email|max:191|unique:users,email',
            'password' => ['nullable', 'string', 'min:6'], // optional - will auto generate if missing
        ]);

        if (empty($data['password'])) {
            $data['password'] = Str::random(12); // will be hashed by model cast
        }

        $user = User::create($data);

        return response()->json([
            'result' => true,
            'user' => $user
        ], 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'sometimes|required|string|max:191',
            'email' => 'sometimes|nullable|email|max:191|unique:users,email,' . $user->id,
            'password' => ['sometimes','nullable','string','min:6'],
        ]);

        if (isset($data['password']) && $data['password'] === '') {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json(['result' => true, 'user' => $user]);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json(['result' => true]);
    }
}

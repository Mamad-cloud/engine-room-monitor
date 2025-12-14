<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PeripheralMode;

class PeripheralModeController extends Controller
{
    public function index()
    {
        return response()->json(PeripheralMode::orderBy('mode')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mode' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
        ]);

        $mode = PeripheralMode::create($data);
        return response()->json($mode, 201);
    }

    public function update(Request $request, $id)
    {
        $mode = PeripheralMode::findOrFail($id);
        $data = $request->validate([
            'mode' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
        ]);
        $mode->update($data);
        return response()->json($mode);
    }

    public function destroy($id)
    {
        $mode = PeripheralMode::findOrFail($id);
        $mode->delete();
        return response()->json(['ok' => true]);
    }
}

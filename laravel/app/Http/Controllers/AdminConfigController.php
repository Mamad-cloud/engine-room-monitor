<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventType;
use App\Models\PeripheralMode;

class AdminConfigController extends Controller
{
    public function eventTypes()
    {
        $types = EventType::orderBy('_id', 'desc')->get();
        return view('event-types.index', compact('types'));
    }

    public function peripheralModes()
    {
        $modes = PeripheralMode::orderBy('_id', 'desc')->get();
        return view('peripheral-modes.index', compact('modes'));
    }
}

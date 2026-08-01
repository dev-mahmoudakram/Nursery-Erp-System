<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::latest()->paginate(20);
        return view('vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        return view('vehicles.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'type' => 'nullable|string',
            'capacity' => 'nullable|numeric',
        ]);

        Vehicle::create($data);

        return redirect()->route('vehicles.index')->with('success', __('messages.vehicle.created'));
    }
}

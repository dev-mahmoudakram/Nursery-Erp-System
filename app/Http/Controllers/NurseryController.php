<?php

namespace App\Http\Controllers;

use App\Models\Nursery;
use Illuminate\Http\Request;

class NurseryController extends Controller
{
    public function index()
    {
        $nurseries = Nursery::withCount('locations', 'batches')->latest()->paginate(15);
        return view('nurseries.index', compact('nurseries'));
    }

    public function create()
    {
        return view('nurseries.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:nurseries,code',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);

        Nursery::create($data);

        return redirect()->route('nurseries.index')->with('success', __('messages.nursery.created'));
    }

    public function edit(Nursery $nursery)
    {
        return view('nurseries.edit', compact('nursery'));
    }

    public function update(Request $request, Nursery $nursery)
    {
        $data = $request->validate([
            'code' => 'required|string|unique:nurseries,code,' . $nursery->id,
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
        ]);
        $data['is_active'] = $request->boolean('is_active');

        $nursery->update($data);

        return redirect()->route('nurseries.index')->with('success', __('messages.nursery.updated'));
    }

    public function destroy(Nursery $nursery)
    {
        $nursery->delete();
        return back()->with('success', __('messages.nursery.deleted'));
    }
}

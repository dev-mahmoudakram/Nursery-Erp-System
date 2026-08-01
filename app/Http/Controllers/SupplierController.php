<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(20);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_code' => 'required|string|unique:suppliers,supplier_code',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'phone' => 'nullable|string',
            'email' => 'nullable|email',
            'city' => 'nullable|string',
        ]);

        Supplier::create($data);

        return redirect()->route('suppliers.index')->with('success', __('messages.supplier.created'));
    }
}

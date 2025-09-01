<?php

namespace App\Http\Controllers\Director;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:director,owner']);
    }

    public function index()
    {
        $suppliers = Supplier::paginate(15);
        return view('director.suppliers.index', compact('suppliers'));
    }

    public function show(Supplier $supplier)
    {
        return view('director.suppliers.show', compact('supplier'));
    }

    public function create()
    {
        return view('director.suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'credit_limit' => 'required|numeric|min:0',
            'credit_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        Supplier::create($validated);
        
        return redirect()->route('director.suppliers.index')->with('success', 'Supplier created successfully!');
    }

    public function edit(Supplier $supplier)
    {
        return view('director.suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'credit_limit' => 'required|numeric|min:0',
            'credit_days' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        
        $supplier->update($validated);
        
        return redirect()->route('director.suppliers.index')->with('success', 'Supplier updated successfully!');
    }
}
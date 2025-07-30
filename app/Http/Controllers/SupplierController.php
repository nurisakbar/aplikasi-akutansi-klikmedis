<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Supplier::query();
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addColumn('actions', function ($row) {
                    return view('suppliers.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('suppliers.index');
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email'
        ]);
        Supplier::create($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:suppliers,email,' . $supplier->id
        ]);
        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier berhasil diperbarui.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $supplier = \App\Models\Supplier::find($id);
        if (!$supplier) {
            return redirect()->route('suppliers.index')->with('error', 'Data supplier tidak ditemukan.');
        }
        return view('suppliers.show', compact('supplier'));
    }
} 
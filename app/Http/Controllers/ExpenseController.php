<?php

namespace App\Http\Controllers;

use App\Services\ExpenseService;
use App\Http\Requests\ExpenseFilterRequest;
use App\Http\Requests\StoreExpenseRequest;
use App\Exports\ExpenseExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    protected $service;
    public function __construct(ExpenseService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = \App\Models\Expense::query();
            if ($request->filled('type')) $query->where('type', $request->type);
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('date_from')) $query->where('date', '>=', $request->date_from);
            if ($request->filled('date_to')) $query->where('date', '<=', $request->date_to);
            return \Yajra\DataTables\Facades\DataTables::of($query)
                ->addColumn('actions', function ($row) {
                    return view('expenses.partials.actions', compact('row'))->render();
                })
                ->editColumn('amount', function($row){
                    return number_format($row->amount,0,',','.');
                })
                ->editColumn('status', function($row){
                    return $row->status === 'paid' ? 'Lunas' : 'Belum Lunas';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('expenses.index');
    }

    public function create()
    {
        return view('expenses.create');
    }

    public function store(StoreExpenseRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('expenses.index')->with('success', 'Data beban berhasil disimpan.');
    }

    public function show($id)
    {
        $expense = $this->service->find($id);
        return view('expenses.show', compact('expense'));
    }

    public function edit($id)
    {
        $expense = $this->service->find($id);
        if (!$expense) {
            return redirect()->route('expenses.index')->with('error', 'Data beban tidak ditemukan.');
        }
        return view('expenses.edit', compact('expense'));
    }

    public function update(StoreExpenseRequest $request, $id)
    {
        $this->service->update($id, $request->validated());
        return redirect()->route('expenses.index')->with('success', 'Data beban berhasil diupdate.');
    }

    public function export(ExpenseFilterRequest $request)
    {
        $filter = $request->validated();
        return Excel::download(new ExpenseExport($filter), 'expenses.xlsx');
    }

    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('expenses.index')->with('success', 'Data beban berhasil dihapus.');
    }

    public function getExpenseTypes()
    {
        $types = \App\Models\Expense::query()->select('type')->distinct()->orderBy('type')->pluck('type');
        return response()->json($types);
    }
} 
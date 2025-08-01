<?php

namespace App\Http\Controllers;

use App\Services\TaxService;
use App\Http\Requests\TaxFilterRequest;
use App\Http\Requests\StoreTaxRequest;
use App\Exports\TaxExport;
use App\Models\AccountancyTax;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class TaxController extends Controller
{
    protected $service;
    public function __construct(TaxService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AccountancyTax::query();
            if ($request->filled('type')) $query->where('type', $request->type);
            if ($request->filled('status')) $query->where('status', $request->status);
            if ($request->filled('date_from')) $query->where('date', '>=', $request->date_from);
            if ($request->filled('date_to')) $query->where('date', '<=', $request->date_to);
            return DataTables::of($query)
                ->addColumn('actions', function ($row) {
                    return view('taxes.partials.actions', compact('row'))->render();
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
        return view('taxes.index');
    }

    public function create()
    {
        return view('taxes.create');
    }

    public function store(StoreTaxRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('taxes.index')->with('success', 'Data pajak berhasil disimpan.');
    }

    public function show(AccountancyTax $tax)
    {
        return view('taxes.show', compact('tax'));
    }

    public function export(Request $request)
    {
        $filter = $request->only(['type', 'status', 'date_from', 'date_to']);
        return Excel::download(new TaxExport($filter), 'taxes.xlsx');
    }
}

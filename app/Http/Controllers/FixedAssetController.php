<?php

namespace App\Http\Controllers;

use App\Services\FixedAssetService;
use App\Http\Requests\FixedAssetFilterRequest;
use App\Http\Requests\StoreFixedAssetRequest;
use App\Exports\FixedAssetExport;
use App\Models\AccountancyFixedAsset;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class FixedAssetController extends Controller
{
    protected $service;
    public function __construct(FixedAssetService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = AccountancyFixedAsset::query();
            return DataTables::of($query)
                ->addColumn('actions', function ($row) {
                    return view('fixed_assets.partials.actions', compact('row'))->render();
                })
                ->editColumn('acquisition_value', function($row){
                    return number_format($row->acquisition_value,0,',','.');
                })
                ->editColumn('residual_value', function($row){
                    return number_format($row->residual_value,0,',','.');
                })
                ->editColumn('depreciation_method', function($row){
                    return $row->depreciation_method == 'straight_line' ? 'Garis Lurus' : 'Saldo Menurun';
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        return view('fixed_assets.index');
    }

    public function create()
    {
        return view('fixed_assets.create');
    }

    public function store(StoreFixedAssetRequest $request)
    {
        $this->service->create($request->validated());
        return redirect()->route('fixed-assets.index')->with('success', 'Aset tetap berhasil disimpan.');
    }

    public function show(AccountancyFixedAsset $fixedAsset)
    {
        $asset = $fixedAsset;
        $depreciation = $this->service->calculateDepreciation($asset);
        return view('fixed_assets.show', compact('asset','depreciation'));
    }

    public function edit(AccountancyFixedAsset $fixedAsset)
    {
        $asset = $fixedAsset;
        return view('fixed_assets.edit', compact('asset'));
    }

    public function export(FixedAssetFilterRequest $request)
    {
        $filter = $request->validated();
        return Excel::download(new FixedAssetExport($filter), 'fixed_assets.xlsx');
    }
}

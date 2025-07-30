<?php

namespace App\Http\Controllers;

use App\Http\Requests\BalanceSheetFilterRequest;
use App\Services\BalanceSheetService;
use App\Exports\BalanceSheetExport;
use Maatwebsite\Excel\Facades\Excel;

class BalanceSheetController extends Controller
{
    protected $service;
    public function __construct(BalanceSheetService $service)
    {
        $this->service = $service;
    }

    public function index(BalanceSheetFilterRequest $request)
    {
        $data = $this->service->getBalanceSheet(
            $request->input('date_to'),
            $request->input('status', 'posted')
        );
        return view('balance_sheet.index', compact('data'));
    }

    public function export(BalanceSheetFilterRequest $request)
    {
        $data = $this->service->getBalanceSheet(
            $request->input('date_to'),
            $request->input('status', 'posted')
        );
        return Excel::download(new BalanceSheetExport($data), 'balance_sheet.xlsx');
    }
} 
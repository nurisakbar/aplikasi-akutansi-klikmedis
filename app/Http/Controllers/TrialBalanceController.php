<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrialBalanceFilterRequest;
use App\Services\TrialBalanceService;
use App\Exports\TrialBalanceExport;
use Maatwebsite\Excel\Facades\Excel;

class TrialBalanceController extends Controller
{
    protected $service;
    public function __construct(TrialBalanceService $service)
    {
        $this->service = $service;
    }

    public function index(TrialBalanceFilterRequest $request)
    {
        $data = $this->service->getTrialBalance(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('status', 'posted')
        );
        return view('trial_balance.index', compact('data'));
    }

    public function export(TrialBalanceFilterRequest $request)
    {
        $data = $this->service->getTrialBalance(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('status', 'posted')
        );
        return Excel::download(new TrialBalanceExport($data), 'trial_balance.xlsx');
    }
} 
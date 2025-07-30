<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfitLossFilterRequest;
use App\Services\ProfitLossService;
use App\Exports\ProfitLossExport;
use Maatwebsite\Excel\Facades\Excel;

class ProfitLossController extends Controller
{
    protected $service;
    public function __construct(ProfitLossService $service)
    {
        $this->service = $service;
    }

    public function index(ProfitLossFilterRequest $request)
    {
        $data = $this->service->getProfitLoss(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('status', 'posted')
        );
        return view('profit_loss.index', compact('data'));
    }

    public function export(ProfitLossFilterRequest $request)
    {
        $data = $this->service->getProfitLoss(
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('status', 'posted')
        );
        return Excel::download(new ProfitLossExport($data), 'profit_loss.xlsx');
    }
} 
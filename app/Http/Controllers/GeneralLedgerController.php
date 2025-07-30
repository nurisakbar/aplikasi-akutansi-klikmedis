<?php

namespace App\Http\Controllers;

use App\Http\Requests\GeneralLedgerFilterRequest;
use App\Services\GeneralLedgerService;
use App\Exports\GeneralLedgerExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ChartOfAccount;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    protected $service;
    public function __construct(GeneralLedgerService $service)
    {
        $this->service = $service;
    }

    public function index(GeneralLedgerFilterRequest $request)
    {
        $accounts = ChartOfAccount::orderBy('code')->get();
        $data = null;
        if ($request->filled('account_id')) {
            $data = $this->service->getLedger(
                $request->input('account_id'),
                $request->input('date_from'),
                $request->input('date_to'),
                $request->input('status', 'posted')
            );
        }
        return view('general_ledger.index', compact('accounts', 'data'));
    }

    public function export(GeneralLedgerFilterRequest $request)
    {
        $data = $this->service->getLedger(
            $request->input('account_id'),
            $request->input('date_from'),
            $request->input('date_to'),
            $request->input('status', 'posted')
        );
        return Excel::download(new GeneralLedgerExport($data), 'general_ledger.xlsx');
    }
} 
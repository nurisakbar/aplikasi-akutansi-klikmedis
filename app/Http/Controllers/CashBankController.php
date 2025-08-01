<?php

namespace App\Http\Controllers;

use App\Services\CashBankService;
use App\Http\Requests\CashBankFilterRequest;
use App\Http\Requests\StoreCashBankRequest;
use Illuminate\Http\Request;
use App\Exports\CashBankExport;
use Maatwebsite\Excel\Facades\Excel;

class CashBankController extends Controller
{
    protected $service;
    public function __construct(CashBankService $service)
    {
        $this->service = $service;
    }

    public function index(CashBankFilterRequest $request)
    {
        $filter = $request->validated();
        $data = $this->service->getTransactions(
            $filter['account_id'] ?? null,
            $filter['date_from'] ?? null,
            $filter['date_to'] ?? null,
            $filter['type'] ?? null,
            $filter['status'] ?? null
        );
        return view('cash_bank.index', [
            'rows' => $data['rows'],
            'saldo' => $data['saldo'],
            'filter' => $filter,
        ]);
    }

    public function export(CashBankFilterRequest $request)
    {
        $filter = $request->validated();
        return Excel::download(new CashBankExport($filter), 'cash_bank.xlsx');
    }

    public function create()
    {
        return view('cash_bank.create');
    }

    public function store(StoreCashBankRequest $request)
    {
        $data = $request->validated();
        if ($request->hasFile('bukti')) {
            $data['bukti'] = $request->file('bukti');
        }
        $this->service->createTransaction($data);
        return redirect()->route('cash-bank.index')->with('success', 'Transaksi kas/bank berhasil disimpan.');
    }
}

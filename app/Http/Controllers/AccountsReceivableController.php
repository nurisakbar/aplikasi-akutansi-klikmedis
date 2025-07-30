<?php

namespace App\Http\Controllers;

use App\Services\AccountsReceivableService;
use App\Http\Requests\AccountsReceivableFilterRequest;
use App\Http\Requests\StoreAccountsReceivableRequest;
use App\Exports\AccountsReceivableExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AccountsReceivableController extends Controller
{
    protected $service;
    public function __construct(AccountsReceivableService $service)
    {
        $this->service = $service;
    }

    public function index(AccountsReceivableFilterRequest $request)
    {
        $filter = $request->validated();
        $rows = $this->service->getReceivables(
            $filter['customer_id'] ?? null,
            $filter['date_from'] ?? null,
            $filter['date_to'] ?? null,
            $filter['status'] ?? null
        );
        $saldo = $this->service->getTotalReceivable($filter['customer_id'] ?? null);
        $aging = $this->service->getAging($filter['customer_id'] ?? null);
        return view('accounts_receivable.index', compact('rows','saldo','aging','filter'));
    }

    public function create()
    {
        return view('accounts_receivable.create');
    }

    public function store(StoreAccountsReceivableRequest $request)
    {
        $this->service->createReceivable($request->validated());
        return redirect()->route('accounts-receivable.index')->with('success', 'Piutang berhasil disimpan.');
    }

    public function export(AccountsReceivableFilterRequest $request)
    {
        $filter = $request->validated();
        return Excel::download(new AccountsReceivableExport($filter), 'accounts_receivable.xlsx');
    }

    public function show($id)
    {
        $receivable = \App\Models\AccountsReceivable::with('customer', 'payments')->findOrFail($id);
        return view('accounts_receivable.show', compact('receivable'));
    }
} 
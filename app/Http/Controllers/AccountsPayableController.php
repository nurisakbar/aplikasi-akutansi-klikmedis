<?php

namespace App\Http\Controllers;

use App\Services\AccountsPayableService;
use App\Http\Requests\AccountsPayableFilterRequest;
use App\Http\Requests\StoreAccountsPayableRequest;
use App\Exports\AccountsPayableExport;
use App\Models\AccountsPayable;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class AccountsPayableController extends Controller
{
    protected $service;
    public function __construct(AccountsPayableService $service)
    {
        $this->service = $service;
    }

    public function index(AccountsPayableFilterRequest $request)
    {
        $filter = $request->validated();
        $rows = $this->service->getPayables(
            $filter['supplier_id'] ?? null,
            $filter['date_from'] ?? null,
            $filter['date_to'] ?? null,
            $filter['status'] ?? null
        );
        $saldo = $this->service->getTotalPayable($filter['supplier_id'] ?? null);
        $aging = $this->service->getAging($filter['supplier_id'] ?? null);
        return view('accounts_payable.index', compact('rows','saldo','aging','filter'));
    }

    public function create()
    {
        return view('accounts_payable.create');
    }

    public function store(StoreAccountsPayableRequest $request)
    {
        $this->service->createPayable($request->validated());
        return redirect()->route('accounts-payable.index')->with('success', 'Hutang berhasil disimpan.');
    }

    public function export(AccountsPayableFilterRequest $request)
    {
        $filter = $request->validated();
        return Excel::download(new AccountsPayableExport($filter), 'accounts_payable.xlsx');
    }

    public function show(AccountsPayable $accountsPayable)
    {
        $payable = $accountsPayable->load('supplier', 'payments');
        return view('accounts_payable.show', compact('payable'));
    }
}

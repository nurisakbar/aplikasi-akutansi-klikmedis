<?php

namespace App\Http\Controllers;

use App\Services\AccountsPayableService;
use App\Http\Requests\StoreAccountsPayablePaymentRequest;
use App\Models\AccountsPayable;
use Illuminate\Http\RedirectResponse;

class AccountsPayablePaymentController extends Controller
{
    protected $service;
    public function __construct(AccountsPayableService $service)
    {
        $this->service = $service;
    }

    public function create(AccountsPayable $accountsPayable)
    {
        $payable = $accountsPayable;
        return view('accounts_payable.payments.create', compact('payable'));
    }

    public function store(StoreAccountsPayablePaymentRequest $request, AccountsPayable $accountsPayable): RedirectResponse
    {
        $this->service->addPayment($accountsPayable->id, $request->validated());
        return redirect()->route('accounts-payable.show', $accountsPayable)->with('success', 'Pembayaran berhasil disimpan.');
    }
}

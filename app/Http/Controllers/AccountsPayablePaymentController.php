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

    public function create($payableId)
    {
        $payable = AccountsPayable::findOrFail($payableId);
        return view('accounts_payable.payments.create', compact('payable'));
    }

    public function store(StoreAccountsPayablePaymentRequest $request, $payableId): RedirectResponse
    {
        $this->service->addPayment($payableId, $request->validated());
        return redirect()->route('accounts-payable.show', $payableId)->with('success', 'Pembayaran berhasil disimpan.');
    }
} 
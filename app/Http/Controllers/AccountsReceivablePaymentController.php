<?php

namespace App\Http\Controllers;

use App\Services\AccountsReceivableService;
use App\Http\Requests\StoreAccountsReceivablePaymentRequest;
use App\Models\AccountsReceivable;
use Illuminate\Http\RedirectResponse;

class AccountsReceivablePaymentController extends Controller
{
    protected $service;
    public function __construct(AccountsReceivableService $service)
    {
        $this->service = $service;
    }

    public function create($receivableId)
    {
        $receivable = AccountsReceivable::findOrFail($receivableId);
        return view('accounts_receivable.payments.create', compact('receivable'));
    }

    public function store(StoreAccountsReceivablePaymentRequest $request, $receivableId): RedirectResponse
    {
        $this->service->addPayment($receivableId, $request->validated());
        return redirect()->route('accounts-receivable.show', $receivableId)->with('success', 'Pembayaran berhasil disimpan.');
    }
} 
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartOfAccountController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('journal-entries/export', [App\Http\Controllers\JournalEntryController::class, 'export'])->name('journal-entries.export');
Route::get('chart-of-accounts/export', [App\Http\Controllers\ChartOfAccountController::class, 'export'])->name('chart-of-accounts.export');
Route::resource('chart-of-accounts', ChartOfAccountController::class);
Route::resource('journal-entries', App\Http\Controllers\JournalEntryController::class);
Route::post('journal-entries/{journalEntry}/post', [App\Http\Controllers\JournalEntryController::class, 'post'])->name('journal-entries.post');
Route::post('journal-entries/upload-attachment', [App\Http\Controllers\JournalEntryController::class, 'uploadAttachment'])->name('journal-entries.upload-attachment');
Route::get('general-ledger', [App\Http\Controllers\GeneralLedgerController::class, 'index'])->name('general-ledger.index');
Route::get('general-ledger/export', [App\Http\Controllers\GeneralLedgerController::class, 'export'])->name('general-ledger.export');
Route::get('trial-balance', [App\Http\Controllers\TrialBalanceController::class, 'index'])->name('trial-balance.index');
Route::get('trial-balance/export', [App\Http\Controllers\TrialBalanceController::class, 'export'])->name('trial-balance.export');
Route::get('balance-sheet', [App\Http\Controllers\BalanceSheetController::class, 'index'])->name('balance-sheet.index');
Route::get('balance-sheet/export', [App\Http\Controllers\BalanceSheetController::class, 'export'])->name('balance-sheet.export');
Route::get('profit-loss', [App\Http\Controllers\ProfitLossController::class, 'index'])->name('profit-loss.index');
Route::get('profit-loss/export', [App\Http\Controllers\ProfitLossController::class, 'export'])->name('profit-loss.export');
Route::get('cash-bank', [\App\Http\Controllers\CashBankController::class, 'index'])->name('cash-bank.index');
Route::get('cash-bank/export', [\App\Http\Controllers\CashBankController::class, 'export'])->name('cash-bank.export');
Route::get('cash-bank/create', [\App\Http\Controllers\CashBankController::class, 'create'])->name('cash-bank.create');
Route::post('cash-bank', [\App\Http\Controllers\CashBankController::class, 'store'])->name('cash-bank.store');
Route::get('accounts-receivable', [\App\Http\Controllers\AccountsReceivableController::class, 'index'])->name('accounts-receivable.index');
Route::get('accounts-receivable/create', [\App\Http\Controllers\AccountsReceivableController::class, 'create'])->name('accounts-receivable.create');
Route::post('accounts-receivable', [\App\Http\Controllers\AccountsReceivableController::class, 'store'])->name('accounts-receivable.store');
Route::get('accounts-receivable/export', [\App\Http\Controllers\AccountsReceivableController::class, 'export'])->name('accounts-receivable.export');
Route::get('accounts-receivable/{id}/payments/create', [\App\Http\Controllers\AccountsReceivablePaymentController::class, 'create'])->name('accounts-receivable.payments.create');
Route::post('accounts-receivable/{id}/payments', [\App\Http\Controllers\AccountsReceivablePaymentController::class, 'store'])->name('accounts-receivable.payments.store');
Route::get('accounts-receivable/{id}', [\App\Http\Controllers\AccountsReceivableController::class, 'show'])->name('accounts-receivable.show');
Route::get('accounts-payable', [\App\Http\Controllers\AccountsPayableController::class, 'index'])->name('accounts-payable.index');
Route::get('accounts-payable/create', [\App\Http\Controllers\AccountsPayableController::class, 'create'])->name('accounts-payable.create');
Route::post('accounts-payable', [\App\Http\Controllers\AccountsPayableController::class, 'store'])->name('accounts-payable.store');
Route::get('accounts-payable/export', [\App\Http\Controllers\AccountsPayableController::class, 'export'])->name('accounts-payable.export');
Route::get('accounts-payable/{id}/payments/create', [\App\Http\Controllers\AccountsPayablePaymentController::class, 'create'])->name('accounts-payable.payments.create');
Route::post('accounts-payable/{id}/payments', [\App\Http\Controllers\AccountsPayablePaymentController::class, 'store'])->name('accounts-payable.payments.store');
Route::get('accounts-payable/{id}', [\App\Http\Controllers\AccountsPayableController::class, 'show'])->name('accounts-payable.show');
Route::resource('fixed-assets', App\Http\Controllers\FixedAssetController::class);
Route::get('fixed-assets/export', [\App\Http\Controllers\FixedAssetController::class, 'export'])->name('fixed-assets.export');
Route::get('taxes/export', [App\Http\Controllers\TaxController::class, 'export'])->name('taxes.export');
Route::resource('taxes', App\Http\Controllers\TaxController::class);
Route::get('expenses/types', [App\Http\Controllers\ExpenseController::class, 'getExpenseTypes'])->name('expenses.types');
Route::get('expenses/export', [App\Http\Controllers\ExpenseController::class, 'export'])->name('expenses.export');
Route::resource('expenses', App\Http\Controllers\ExpenseController::class);
Route::resource('customers', \App\Http\Controllers\CustomerController::class);
Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);

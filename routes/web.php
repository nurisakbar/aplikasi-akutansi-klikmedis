<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChartOfAccountController;
use App\Http\Controllers\JournalEntryController;
use App\Http\Controllers\GeneralLedgerController;
use App\Http\Controllers\TrialBalanceController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\ProfitLossController;
use App\Http\Controllers\CashBankController;
use App\Http\Controllers\AccountsReceivableController;
use App\Http\Controllers\AccountsReceivablePaymentController;
use App\Http\Controllers\AccountsPayableController;
use App\Http\Controllers\AccountsPayablePaymentController;
use App\Http\Controllers\FixedAssetController;
use App\Http\Controllers\TaxController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SupplierController;

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

/*
|--------------------------------------------------------------------------
| DATA MASTER ROUTES
|--------------------------------------------------------------------------
| Routes untuk data master/referensi yang relatif statis dan digunakan
| sebagai referensi oleh data transaksional lainnya
*/

// Master Data - Chart of Accounts (Bagan Akun)
Route::get('chart-of-accounts/export', [ChartOfAccountController::class, 'export'])->name('chart-of-accounts.export');
Route::resource('chart-of-accounts', ChartOfAccountController::class);

// Master Data - Fixed Assets (Aset Tetap)
Route::get('fixed-assets/export', [FixedAssetController::class, 'export'])->name('fixed-assets.export');
Route::resource('fixed-assets', FixedAssetController::class);

// Master Data - Taxes (Pajak)
Route::get('taxes/export', [TaxController::class, 'export'])->name('taxes.export');
Route::resource('taxes', TaxController::class);

// Master Data - Customers (Pelanggan)
Route::resource('customers', CustomerController::class);

// Master Data - Suppliers (Pemasok)
Route::resource('suppliers', SupplierController::class);

/*
|--------------------------------------------------------------------------
| DATA TRANSAKSIONAL ROUTES
|--------------------------------------------------------------------------
| Routes untuk data transaksional yang bersifat dinamis dan mencatat
| aktivitas bisnis harian
*/

// Transactional Data - Journal Entries (Jurnal Umum)
Route::get('journal-entries/export', [JournalEntryController::class, 'export'])->name('journal-entries.export');
Route::resource('journal-entries', JournalEntryController::class);
Route::post('journal-entries/{journalEntry}/post', [JournalEntryController::class, 'post'])->name('journal-entries.post');
Route::post('journal-entries/upload-attachment', [JournalEntryController::class, 'uploadAttachment'])->name('journal-entries.upload-attachment');

// Transactional Data - Cash & Bank (Kas & Bank)
Route::get('cash-bank', [CashBankController::class, 'index'])->name('cash-bank.index');
Route::get('cash-bank/export', [CashBankController::class, 'export'])->name('cash-bank.export');
Route::get('cash-bank/create', [CashBankController::class, 'create'])->name('cash-bank.create');
Route::post('cash-bank', [CashBankController::class, 'store'])->name('cash-bank.store');

// Transactional Data - Accounts Receivable (Piutang)
Route::get('accounts-receivable', [AccountsReceivableController::class, 'index'])->name('accounts-receivable.index');
Route::get('accounts-receivable/create', [AccountsReceivableController::class, 'create'])->name('accounts-receivable.create');
Route::post('accounts-receivable', [AccountsReceivableController::class, 'store'])->name('accounts-receivable.store');
Route::get('accounts-receivable/export', [AccountsReceivableController::class, 'export'])->name('accounts-receivable.export');
Route::get('accounts-receivable/{id}/payments/create', [AccountsReceivablePaymentController::class, 'create'])->name('accounts-receivable.payments.create');
Route::post('accounts-receivable/{id}/payments', [AccountsReceivablePaymentController::class, 'store'])->name('accounts-receivable.payments.store');
Route::get('accounts-receivable/{id}', [AccountsReceivableController::class, 'show'])->name('accounts-receivable.show');

// Transactional Data - Accounts Payable (Hutang)
Route::get('accounts-payable', [AccountsPayableController::class, 'index'])->name('accounts-payable.index');
Route::get('accounts-payable/create', [AccountsPayableController::class, 'create'])->name('accounts-payable.create');
Route::post('accounts-payable', [AccountsPayableController::class, 'store'])->name('accounts-payable.store');
Route::get('accounts-payable/export', [AccountsPayableController::class, 'export'])->name('accounts-payable.export');
Route::get('accounts-payable/{id}/payments/create', [AccountsPayablePaymentController::class, 'create'])->name('accounts-payable.payments.create');
Route::post('accounts-payable/{id}/payments', [AccountsPayablePaymentController::class, 'store'])->name('accounts-payable.payments.store');
Route::get('accounts-payable/{id}', [AccountsPayableController::class, 'show'])->name('accounts-payable.show');

// Transactional Data - Expenses (Biaya/Pengeluaran)
Route::get('expenses/types', [ExpenseController::class, 'getExpenseTypes'])->name('expenses.types');
Route::get('expenses/export', [ExpenseController::class, 'export'])->name('expenses.export');
Route::resource('expenses', ExpenseController::class);

/*
|--------------------------------------------------------------------------
| LAPORAN/REPORT ROUTES
|--------------------------------------------------------------------------
| Routes untuk menampilkan laporan keuangan berdasarkan data transaksional
*/

// Financial Reports - General Ledger (Buku Besar)
Route::get('general-ledger', [App\Http\Controllers\GeneralLedgerController::class, 'index'])->name('general-ledger.index');
Route::get('general-ledger/export', [App\Http\Controllers\GeneralLedgerController::class, 'export'])->name('general-ledger.export');

// Financial Reports - Trial Balance (Neraca Saldo)
Route::get('trial-balance', [App\Http\Controllers\TrialBalanceController::class, 'index'])->name('trial-balance.index');
Route::get('trial-balance/export', [App\Http\Controllers\TrialBalanceController::class, 'export'])->name('trial-balance.export');

// Financial Reports - Balance Sheet (Neraca)
Route::get('balance-sheet', [App\Http\Controllers\BalanceSheetController::class, 'index'])->name('balance-sheet.index');
Route::get('balance-sheet/export', [App\Http\Controllers\BalanceSheetController::class, 'export'])->name('balance-sheet.export');

// Financial Reports - Profit & Loss (Laba Rugi)
Route::get('profit-loss', [App\Http\Controllers\ProfitLossController::class, 'index'])->name('profit-loss.index');
Route::get('profit-loss/export', [App\Http\Controllers\ProfitLossController::class, 'export'])->name('profit-loss.export');

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
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ProfileController;

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

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES
|--------------------------------------------------------------------------
| Routes untuk autentikasi (login, register, logout)
*/

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login'])->name('login.post');
Route::get('register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('register', [RegisterController::class, 'register'])->name('register.post');
Route::post('logout', [LogoutController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| PROTECTED ROUTES (Require Authentication)
|--------------------------------------------------------------------------
| Routes yang memerlukan autentikasi dan terkait dengan data akuntansi
*/

// Routes untuk semua user (termasuk superadmin)
Route::middleware(['auth'])->group(function () {
    // Profile routes
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Chart of Accounts - accessible by all authenticated users
    Route::get('chart-of-accounts/export', [ChartOfAccountController::class, 'export'])->name('chart-of-accounts.export');
    Route::resource('chart-of-accounts', ChartOfAccountController::class);
});

// Routes yang memerlukan company (exclude superadmin)
Route::middleware(['auth', 'has.company'])->group(function () {
    /*
    |--------------------------------------------------------------------------
    | DATA MASTER ROUTES
    |--------------------------------------------------------------------------
    | Routes untuk data master/referensi yang relatif statis dan digunakan
    | sebagai referensi oleh data transaksional lainnya
    */

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
Route::get('accounts-receivable/{accountsReceivable}/payments/create', [AccountsReceivablePaymentController::class, 'create'])->name('accounts-receivable.payments.create');
Route::post('accounts-receivable/{accountsReceivable}/payments', [AccountsReceivablePaymentController::class, 'store'])->name('accounts-receivable.payments.store');
Route::get('accounts-receivable/{accountsReceivable}', [AccountsReceivableController::class, 'show'])->name('accounts-receivable.show');

// Transactional Data - Accounts Payable (Hutang)
Route::get('accounts-payable', [AccountsPayableController::class, 'index'])->name('accounts-payable.index');
Route::get('accounts-payable/create', [AccountsPayableController::class, 'create'])->name('accounts-payable.create');
Route::post('accounts-payable', [AccountsPayableController::class, 'store'])->name('accounts-payable.store');
Route::get('accounts-payable/export', [AccountsPayableController::class, 'export'])->name('accounts-payable.export');
Route::get('accounts-payable/{accountsPayable}/payments/create', [AccountsPayablePaymentController::class, 'create'])->name('accounts-payable.payments.create');
Route::post('accounts-payable/{accountsPayable}/payments', [AccountsPayablePaymentController::class, 'store'])->name('accounts-payable.payments.store');
Route::get('accounts-payable/{accountsPayable}', [AccountsPayableController::class, 'show'])->name('accounts-payable.show');

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
Route::get('general-ledger', [GeneralLedgerController::class, 'index'])->name('general-ledger.index');
Route::get('general-ledger/export', [GeneralLedgerController::class, 'export'])->name('general-ledger.export');

// Financial Reports - Trial Balance (Neraca Saldo)
Route::get('trial-balance', [TrialBalanceController::class, 'index'])->name('trial-balance.index');
Route::get('trial-balance/export', [TrialBalanceController::class, 'export'])->name('trial-balance.export');

// Financial Reports - Balance Sheet (Neraca)
Route::get('balance-sheet', [BalanceSheetController::class, 'index'])->name('balance-sheet.index');
Route::get('balance-sheet/export', [BalanceSheetController::class, 'export'])->name('balance-sheet.export');

// Financial Reports - Profit & Loss (Laba Rugi)
Route::get('profit-loss', [ProfitLossController::class, 'index'])->name('profit-loss.index');
Route::get('profit-loss/export', [ProfitLossController::class, 'export'])->name('profit-loss.export');
});





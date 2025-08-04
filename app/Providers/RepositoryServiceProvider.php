<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Auth\AuthRepository;
use App\Repositories\Auth\AuthRepositoryInterface;
use App\Services\Auth\AuthService;
use App\Services\Auth\AuthServiceInterface;

// Repository Interfaces
use App\Repositories\Interfaces\ChartOfAccountRepositoryInterface;
use App\Repositories\Interfaces\FixedAssetRepositoryInterface;
use App\Repositories\Interfaces\TaxRepositoryInterface;
use App\Repositories\Interfaces\AccountancyJournalEntryRepositoryInterface;
use App\Repositories\Interfaces\CashBankRepositoryInterface;
use App\Repositories\Interfaces\AccountsReceivableRepositoryInterface;
use App\Repositories\Interfaces\AccountsReceivablePaymentRepositoryInterface;
use App\Repositories\Interfaces\AccountsPayableRepositoryInterface;
use App\Repositories\Interfaces\AccountsPayablePaymentRepositoryInterface;
use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Repositories\Interfaces\GeneralLedgerRepositoryInterface;
use App\Repositories\Interfaces\BalanceSheetRepositoryInterface;
use App\Repositories\Interfaces\ProfitLossRepositoryInterface;
use App\Repositories\Interfaces\TrialBalanceRepositoryInterface;

// Repository Implementations
use App\Repositories\ChartOfAccountRepository;
use App\Repositories\FixedAssetRepository;
use App\Repositories\TaxRepository;
use App\Repositories\AccountancyJournalEntryRepository;
use App\Repositories\CashBankRepository;
use App\Repositories\AccountsReceivableRepository;
use App\Repositories\AccountsReceivablePaymentRepository;
use App\Repositories\AccountsPayableRepository;
use App\Repositories\AccountsPayablePaymentRepository;
use App\Repositories\ExpenseRepository;
use App\Repositories\GeneralLedgerRepository;
use App\Repositories\BalanceSheetRepository;
use App\Repositories\ProfitLossRepository;
use App\Repositories\TrialBalanceRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Auth Bindings
        $this->app->bind(AuthRepositoryInterface::class, AuthRepository::class);
        $this->app->bind(AuthServiceInterface::class, AuthService::class);

        // Repository Bindings
        $this->app->bind(ChartOfAccountRepositoryInterface::class, ChartOfAccountRepository::class);
        $this->app->bind(FixedAssetRepositoryInterface::class, FixedAssetRepository::class);
        $this->app->bind(TaxRepositoryInterface::class, TaxRepository::class);
        $this->app->bind(AccountancyJournalEntryRepositoryInterface::class, AccountancyJournalEntryRepository::class);
        $this->app->bind(CashBankRepositoryInterface::class, CashBankRepository::class);
        $this->app->bind(AccountsReceivableRepositoryInterface::class, AccountsReceivableRepository::class);
        $this->app->bind(AccountsReceivablePaymentRepositoryInterface::class, AccountsReceivablePaymentRepository::class);
        $this->app->bind(AccountsPayableRepositoryInterface::class, AccountsPayableRepository::class);
        $this->app->bind(AccountsPayablePaymentRepositoryInterface::class, AccountsPayablePaymentRepository::class);
        $this->app->bind(ExpenseRepositoryInterface::class, ExpenseRepository::class);
        $this->app->bind(GeneralLedgerRepositoryInterface::class, GeneralLedgerRepository::class);
        $this->app->bind(BalanceSheetRepositoryInterface::class, BalanceSheetRepository::class);
        $this->app->bind(ProfitLossRepositoryInterface::class, ProfitLossRepository::class);
        $this->app->bind(TrialBalanceRepositoryInterface::class, TrialBalanceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interfaces\ChartOfAccountRepositoryInterface;
use App\Repositories\ChartOfAccountRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Interfaces\ChartOfAccountRepositoryInterface::class,
            \App\Repositories\ChartOfAccountRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\JournalEntryRepositoryInterface::class,
            \App\Repositories\JournalEntryRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\GeneralLedgerRepositoryInterface::class,
            \App\Repositories\GeneralLedgerRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\TrialBalanceRepositoryInterface::class,
            \App\Repositories\TrialBalanceRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\BalanceSheetRepositoryInterface::class,
            \App\Repositories\BalanceSheetRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\ProfitLossRepositoryInterface::class,
            \App\Repositories\ProfitLossRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\CashBankRepositoryInterface::class,
            \App\Repositories\CashBankRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\AccountsReceivableRepositoryInterface::class,
            \App\Repositories\AccountsReceivableRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\AccountsReceivablePaymentRepositoryInterface::class,
            \App\Repositories\AccountsReceivablePaymentRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\AccountsPayableRepositoryInterface::class,
            \App\Repositories\AccountsPayableRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\AccountsPayablePaymentRepositoryInterface::class,
            \App\Repositories\AccountsPayablePaymentRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\FixedAssetRepositoryInterface::class,
            \App\Repositories\FixedAssetRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\TaxRepositoryInterface::class,
            \App\Repositories\TaxRepository::class
        );
        $this->app->bind(
            \App\Repositories\Interfaces\ExpenseRepositoryInterface::class,
            \App\Repositories\ExpenseRepository::class
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
} 
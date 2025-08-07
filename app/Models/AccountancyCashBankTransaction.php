<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use App\Enums\CashBankTransactionType;
use App\Enums\CashBankTransactionStatus;

class AccountancyCashBankTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'accountancy_cash_bank_transactions';
    
    // UUID Configuration
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'accountancy_chart_of_account_id',
        'accountancy_company_id',
        'date',
        'type',
        'amount',
        'description',
        'status',
        'bukti',
        'reference',
        'created_by'
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'type' => CashBankTransactionType::class,
        'status' => CashBankTransactionStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid()->toString();
            }
        });
    }

    /**
     * Get the chart of account that owns the transaction
     */
    public function accountancyChartOfAccount(): BelongsTo
    {
        return $this->belongsTo(AccountancyChartOfAccount::class, 'accountancy_chart_of_account_id');
    }

    /**
     * Get the company that owns the transaction
     */
    public function accountancyCompany(): BelongsTo
    {
        return $this->belongsTo(AccountancyCompany::class, 'accountancy_company_id');
    }

    /**
     * Check if transaction is draft
     */
    public function isDraft(): bool
    {
        return $this->status->isDraft();
    }

    /**
     * Check if transaction is posted
     */
    public function isPosted(): bool
    {
        return $this->status->isPosted();
    }

    /**
     * Get formatted status
     */
    public function getFormattedStatusAttribute(): string
    {
        return $this->status->getLabel();
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status->getBadgeClass();
    }

    /**
     * Get formatted type
     */
    public function getFormattedTypeAttribute(): string
    {
        return $this->type->getLabel();
    }

    /**
     * Get type badge class
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return $this->type->getBadgeClass();
    }

    /**
     * Scope to filter by company
     */
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('accountancy_company_id', $companyId);
    }

    /**
     * Scope to filter by account
     */
    public function scopeByAccount($query, $accountId)
    {
        return $query->where('accountancy_chart_of_account_id', $accountId);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeByDateRange($query, $dateFrom, $dateTo)
    {
        if ($dateFrom) {
            $query->where('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('date', '<=', $dateTo);
        }
        return $query;
    }

    /**
     * Scope to filter by type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}

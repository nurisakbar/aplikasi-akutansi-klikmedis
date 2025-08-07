<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\CustomerStatus;

class AccountancyCustomer extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $table = 'accountancy_customers';
    
    // UUID Configuration
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'accountancy_company_id',
        'code',
        'name',
        'company_name',
        'email',
        'phone',
        'address',
        'npwp',
        'credit_limit',
        'status',
        'contact_person',
        'payment_terms'
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'status' => CustomerStatus::class,
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = \Illuminate\Support\Str::uuid()->toString();
            }
            if (empty($model->code)) {
                $model->code = $model->generateCustomerCode();
            }
        });
    }

    /**
     * Get the company that owns the customer
     */
    public function accountancyCompany(): BelongsTo
    {
        return $this->belongsTo(AccountancyCompany::class, 'accountancy_company_id');
    }

    /**
     * Get the accounts receivable for the customer
     */
    public function accountsReceivables(): HasMany
    {
        return $this->hasMany(AccountsReceivable::class, 'customer_id');
    }

    /**
     * Generate unique customer code
     */
    public function generateCustomerCode(): string
    {
        $year = date('Y');
        $prefix = 'CUST-' . $year . '-';
        $last = static::where('code', 'like', $prefix . '%')
            ->orderByDesc('code')
            ->first();
        
        $lastNumber = 0;
        if ($last && preg_match('/CUST-' . $year . '-(\d+)/', $last->code, $m)) {
            $lastNumber = (int)$m[1];
        }
        
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Check if customer is active
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
     * Check if customer is inactive
     */
    public function isInactive(): bool
    {
        return $this->status->isInactive();
    }

    /**
     * Check if customer is on hold
     */
    public function isOnHold(): bool
    {
        return $this->status->isOnHold();
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
     * Get formatted credit limit
     */
    public function getFormattedCreditLimitAttribute(): string
    {
        return number_format($this->credit_limit, 0, ',', '.');
    }

    /**
     * Get current outstanding balance
     */
    public function getOutstandingBalanceAttribute(): float
    {
        return $this->accountsReceivables()
            ->where('status', 'unpaid')
            ->sum('amount');
    }

    /**
     * Get available credit
     */
    public function getAvailableCreditAttribute(): float
    {
        return $this->credit_limit - $this->outstanding_balance;
    }

    /**
     * Check if customer has available credit
     */
    public function hasAvailableCredit(float $amount = 0): bool
    {
        return $this->available_credit >= $amount;
    }

    /**
     * Scope by company
     */
    public function scopeByCompany($query, $companyId)
    {
        return $query->where('accountancy_company_id', $companyId);
    }

    /**
     * Scope by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope active customers
     */
    public function scopeActive($query)
    {
        return $query->where('status', CustomerStatus::ACTIVE);
    }
}

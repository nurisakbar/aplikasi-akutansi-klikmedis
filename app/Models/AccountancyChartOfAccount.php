<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class AccountancyChartOfAccount extends Model
{
    use HasUuids, SoftDeletes;

    /**
     * Account Types
     */
    public const TYPE_ASSET = 'asset';
    public const TYPE_LIABILITY = 'liability';
    public const TYPE_EQUITY = 'equity';
    public const TYPE_REVENUE = 'revenue';
    public const TYPE_EXPENSE = 'expense';

    /**
     * Account Categories
     */
    public const CATEGORY_CURRENT_ASSET = 'current_asset';
    public const CATEGORY_FIXED_ASSET = 'fixed_asset';
    public const CATEGORY_OTHER_ASSET = 'other_asset';
    public const CATEGORY_CURRENT_LIABILITY = 'current_liability';
    public const CATEGORY_LONG_TERM_LIABILITY = 'long_term_liability';
    public const CATEGORY_EQUITY = 'equity';
    public const CATEGORY_OPERATING_REVENUE = 'operating_revenue';
    public const CATEGORY_OTHER_REVENUE = 'other_revenue';
    public const CATEGORY_OPERATING_EXPENSE = 'operating_expense';
    public const CATEGORY_OTHER_EXPENSE = 'other_expense';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accountancy_chart_of_accounts';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'id',
        'accountancy_company_id',
        'code',
        'name',
        'type',
        'category',
        'parent_id',
        'description',
        'is_active',
        'level',
        'path'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'level' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get valid account types.
     *
     * @return array<string>
     */
    public static function getValidTypes(): array
    {
        return [
            self::TYPE_ASSET,
            self::TYPE_LIABILITY,
            self::TYPE_EQUITY,
            self::TYPE_REVENUE,
            self::TYPE_EXPENSE
        ];
    }

    /**
     * Get valid account categories.
     *
     * @return array<string>
     */
    public static function getValidCategories(): array
    {
        return [
            self::CATEGORY_CURRENT_ASSET,
            self::CATEGORY_FIXED_ASSET,
            self::CATEGORY_OTHER_ASSET,
            self::CATEGORY_CURRENT_LIABILITY,
            self::CATEGORY_LONG_TERM_LIABILITY,
            self::CATEGORY_EQUITY,
            self::CATEGORY_OPERATING_REVENUE,
            self::CATEGORY_OTHER_REVENUE,
            self::CATEGORY_OPERATING_EXPENSE,
            self::CATEGORY_OTHER_EXPENSE
        ];
    }

    /**
     * Get the parent account.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    /**
     * Get the direct children accounts.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get all descendant accounts.
     */
    public function allChildren()
    {
        return $this->children()->with('allChildren');
    }

    /**
     * Scope a query to only include active accounts.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by account type.
     */
    public function scopeByType(Builder $query, string $type): void
    {
        $query->where('type', $type);
    }

    /**
     * Scope a query to filter by account category.
     */
    public function scopeByCategory(Builder $query, string $category): void
    {
        $query->where('category', $category);
    }

    /**
     * Scope a query to only include root accounts.
     */
    public function scopeRoot(Builder $query): void
    {
        $query->whereNull('parent_id');
    }

    /**
     * Check if the account is a root account.
     */
    public function isRoot(): bool
    {
        return is_null($this->parent_id);
    }

    /**
     * Check if the account is a leaf node (has no children).
     */
    public function isLeaf(): bool
    {
        return $this->children()->count() === 0;
    }

    /**
     * Get the full account name (code - name).
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->code} - {$this->name}";
    }

    /**
     * Update the account's hierarchical path.
     */
    public function updatePath(): void
    {
        if ($this->isRoot()) {
            $this->path = $this->id;
            $this->level = 1;
        } else {
            $parent = $this->parent;
            $this->path = $parent->path . '/' . $this->id;
            $this->level = $parent->level + 1;
        }
        $this->save();
    }

    /**
     * Get accounts by company ID.
     */
    public static function getByCompanyId(string $companyId): Builder
    {
        return static::where('accountancy_company_id', $companyId);
    }

    /**
     * Get the company that owns the chart of account.
     */
    public function accountancyCompany()
    {
        return $this->belongsTo(AccountancyCompany::class, 'accountancy_company_id');
    }

    /**
     * Get formatted category name.
     */
    public function getFormattedCategoryAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->category));
    }

    /**
     * Get badge class based on account type.
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            self::TYPE_ASSET => 'success',
            self::TYPE_LIABILITY => 'danger',
            self::TYPE_EQUITY => 'warning',
            self::TYPE_REVENUE => 'info',
            default => 'secondary'
        };
    }
}

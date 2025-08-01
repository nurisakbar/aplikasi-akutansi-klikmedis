<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Enums\AccountCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class AccountancyChartOfAccount extends Model
{
    use HasUuids, SoftDeletes;

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
        'type' => AccountType::class,
        'category' => AccountCategory::class,
        'is_active' => 'boolean',
        'level' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Get valid account types.
     *
     * @return array<string, string>
     */
    public static function getValidTypes(): array
    {
        return AccountType::getOptions();
    }

    /**
     * Get valid account categories.
     *
     * @return array<string, string>
     */
    public static function getValidCategories(): array
    {
        return AccountCategory::getOptions();
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
     * Scope a query to filter by company ID.
     */
    public function scopeGetByCompanyId(Builder $query, string $companyId): void
    {
        $query->where('accountancy_company_id', $companyId);
    }

    /**
     * Get accounts by company ID (static method for backward compatibility).
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
        return $this->category->getLabel();
    }

    /**
     * Get badge class based on account type.
     */
    public function getTypeBadgeClassAttribute(): string
    {
        return match($this->type) {
            AccountType::ASSET => 'success',
            AccountType::LIABILITY => 'danger',
            AccountType::EQUITY => 'warning',
            AccountType::REVENUE => 'info',
            default => 'secondary'
        };
    }
}

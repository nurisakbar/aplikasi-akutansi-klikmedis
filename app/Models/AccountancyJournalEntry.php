<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\JournalEntryStatus;

class AccountancyJournalEntry extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'accountancy_company_id',
        'journal_number',
        'date',
        'description',
        'reference',
        'attachment',
        'status',
        'history',
        'created_by',
    ];

    protected $casts = [
        'history' => 'array',
        'status' => JournalEntryStatus::class,
    ];

    public static function generateJournalNumber(): string
    {
        $year = date('Y');
        $prefix = 'JU-' . $year . '-';
        $last = static::whereYear('date', $year)
            ->whereNotNull('journal_number')
            ->orderByDesc('journal_number')
            ->first();
        $lastNumber = 0;
        if ($last && preg_match('/JU-' . $year . '-(\d+)/', $last->journal_number, $m)) {
            $lastNumber = (int)$m[1];
        }
        return $prefix . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
    }

    public function accountancyJournalEntryLines()
    {
        return $this->hasMany(AccountancyJournalEntryLine::class, 'journal_entry_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function accountancyCompany(): BelongsTo
    {
        return $this->belongsTo(AccountancyCompany::class, 'accountancy_company_id');
    }

    public function isDraft(): bool
    {
        return $this->status->isDraft();
    }

    public function isPosted(): bool
    {
        return $this->status->isPosted();
    }

    /**
     * Get formatted status label.
     */
    public function getFormattedStatusAttribute(): string
    {
        return $this->status->getLabel();
    }

    /**
     * Get status badge class.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return $this->status->getBadgeClass();
    }
}

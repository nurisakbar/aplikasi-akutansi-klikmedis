<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AccountancyJournalEntryLine extends Model
{
    use HasUuids, SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'journal_entry_id',
        'chart_of_account_id',
        'debit',
        'credit',
        'description',
    ];

    public function accountancyJournalEntry(): BelongsTo
    {
        return $this->belongsTo(AccountancyJournalEntry::class, 'journal_entry_id');
    }

    public function accountancyChartOfAccount(): BelongsTo
    {
        return $this->belongsTo(AccountancyChartOfAccount::class, 'chart_of_account_id');
    }
}

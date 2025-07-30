<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JournalEntryLine extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'akuntansi_journal_entry_lines';
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

    public function journalEntry(): BelongsTo
    {
        return $this->belongsTo(JournalEntry::class, 'journal_entry_id');
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'chart_of_account_id');
    }
} 
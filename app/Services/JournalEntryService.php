<?php

namespace App\Services;

use App\Models\JournalEntry;
use App\Models\JournalEntryLine;
use App\Repositories\Interfaces\JournalEntryRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;

class JournalEntryService
{
    protected $repository;

    public function __construct(JournalEntryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    private function addHistory(JournalEntry $entry, string $action, array $changes = []): void
    {
        $history = $entry->history ?? [];
        $history[] = [
            'action' => $action,
            'user' => Auth::user() ? Auth::user()->name : 'system',
            'at' => now()->toDateTimeString(),
            'changes' => $changes,
        ];
        $entry->history = $history;
        $entry->save();
    }

    public function create(array $data): JournalEntry
    {
        $lines = $data['lines'];
        $totalDebit = collect($lines)->sum('debit');
        $totalCredit = collect($lines)->sum('credit');
        if ($totalDebit != $totalCredit) {
            throw new \Exception('Total debit dan kredit harus seimbang.');
        }
        return DB::transaction(function () use ($data, $lines) {
            $data['journal_number'] = \App\Models\JournalEntry::generateJournalNumber();
            $data['status'] = $data['status'] ?? 'draft';
            // Handle attachment
            if (isset($data['attachment']) && $data['attachment']) {
                $file = $data['attachment'];
                $filename = uniqid('att_') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('journal_attachments', $filename, 'public');
                $data['attachment'] = $filename;
            } else {
                $data['attachment'] = null;
            }
            $entry = $this->repository->create([
                'date' => $data['date'],
                'journal_number' => $data['journal_number'],
                'description' => $data['description'] ?? null,
                'reference' => $data['reference'] ?? null,
                'attachment' => $data['attachment'],
                'status' => $data['status'],
                'created_by' => Auth::id(),
            ]);
            foreach ($lines as $line) {
                $entry->lines()->create($line);
            }
            $this->addHistory($entry, 'create');
            return $entry;
        });
    }

    public function update(JournalEntry $entry, array $data): JournalEntry
    {
        if ($entry->isPosted()) {
            throw new \Exception('Jurnal yang sudah posted tidak bisa diedit.');
        }
        $old = $entry->toArray();
        $lines = $data['lines'];
        $totalDebit = collect($lines)->sum('debit');
        $totalCredit = collect($lines)->sum('credit');
        if ($totalDebit != $totalCredit) {
            throw new \Exception('Total debit dan kredit harus seimbang.');
        }
        return DB::transaction(function () use ($entry, $data, $lines, $old) {
            // Handle attachment
            if (isset($data['attachment']) && $data['attachment']) {
                $file = $data['attachment'];
                $filename = uniqid('att_') . '.' . $file->getClientOriginalExtension();
                $file->storeAs('journal_attachments', $filename, 'public');
                $data['attachment'] = $filename;
            } else {
                unset($data['attachment']);
            }
            $this->repository->update($entry, [
                'date' => $data['date'],
                'description' => $data['description'] ?? null,
                'reference' => $data['reference'] ?? null,
                'attachment' => $data['attachment'] ?? $entry->attachment,
                'status' => $data['status'] ?? $entry->status,
            ]);
            $entry->lines()->delete();
            foreach ($lines as $line) {
                $entry->lines()->create($line);
            }
            $new = $entry->fresh()->toArray();
            $changes = Arr::except(array_diff_assoc($new, $old), ['updated_at', 'history']);
            $this->addHistory($entry, 'update', $changes);
            return $entry->refresh();
        });
    }

    public function delete(JournalEntry $entry): bool
    {
        if ($entry->isPosted()) {
            throw new \Exception('Jurnal yang sudah posted tidak bisa dihapus.');
        }
        return $this->repository->delete($entry);
    }

    public function post(JournalEntry $entry): void
    {
        if ($entry->isPosted()) {
            throw new \Exception('Jurnal sudah diposting.');
        }
        $entry->status = 'posted';
        $entry->save();
        $this->addHistory($entry, 'post');
    }
} 
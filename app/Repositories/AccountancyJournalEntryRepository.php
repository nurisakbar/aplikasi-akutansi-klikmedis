<?php

namespace App\Repositories;

use App\Models\AccountancyJournalEntry;
use App\Repositories\Interfaces\AccountancyJournalEntryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class AccountancyJournalEntryRepository implements AccountancyJournalEntryRepositoryInterface
{
    public function all(): Collection
    {
        return AccountancyJournalEntry::with('accountancyJournalEntryLines')->orderByDesc('date')->get();
    }

    public function find(string $id): ?AccountancyJournalEntry
    {
        return AccountancyJournalEntry::with('accountancyJournalEntryLines')->find($id);
    }

    public function create(array $data): AccountancyJournalEntry
    {
        return AccountancyJournalEntry::create($data);
    }

    public function update(AccountancyJournalEntry $entry, array $data): bool
    {
        return $entry->update($data);
    }

    public function delete(AccountancyJournalEntry $entry): bool
    {
        return $entry->delete();
    }
}

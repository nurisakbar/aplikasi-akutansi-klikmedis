<?php

namespace App\Repositories\AccountancyJournalEntry;

use App\Models\AccountancyJournalEntry;
use App\Repositories\AccountancyJournalEntry\AccountancyJournalEntryRepositoryInterface;

class AccountancyJournalEntryRepository implements AccountancyJournalEntryRepositoryInterface
{
    public function create(array $data): AccountancyJournalEntry
    {
        return AccountancyJournalEntry::create($data);
    }

    public function update(AccountancyJournalEntry $journalEntry, array $data): AccountancyJournalEntry
    {
        $journalEntry->update($data);
        return $journalEntry->fresh();
    }

    public function delete(AccountancyJournalEntry $journalEntry): bool
    {
        return $journalEntry->delete();
    }

    public function findById(string $id): ?AccountancyJournalEntry
    {
        return AccountancyJournalEntry::find($id);
    }

    public function getAll()
    {
        return AccountancyJournalEntry::all();
    }
}

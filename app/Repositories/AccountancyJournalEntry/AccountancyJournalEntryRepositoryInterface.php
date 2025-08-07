<?php

namespace App\Repositories\AccountancyJournalEntry;

use App\Models\AccountancyJournalEntry;

interface AccountancyJournalEntryRepositoryInterface
{
    public function create(array $data): AccountancyJournalEntry;
    
    public function update(AccountancyJournalEntry $journalEntry, array $data): AccountancyJournalEntry;
    
    public function delete(AccountancyJournalEntry $journalEntry): bool;
    
    public function findById(string $id): ?AccountancyJournalEntry;
    
    public function getAll();
}

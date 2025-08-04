<?php

namespace App\Repositories\Interfaces;

use App\Models\AccountancyJournalEntry;
use Illuminate\Database\Eloquent\Collection;

interface AccountancyJournalEntryRepositoryInterface
{
    public function all(): Collection;
    public function find(string $id): ?AccountancyJournalEntry;
    public function create(array $data): AccountancyJournalEntry;
    public function update(AccountancyJournalEntry $entry, array $data): bool;
    public function delete(AccountancyJournalEntry $entry): bool;
}

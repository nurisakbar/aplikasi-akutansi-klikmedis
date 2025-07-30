<?php

namespace App\Repositories;

use App\Models\JournalEntry;
use App\Repositories\Interfaces\JournalEntryRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class JournalEntryRepository implements JournalEntryRepositoryInterface
{
    public function all(): Collection
    {
        return JournalEntry::with('lines')->orderByDesc('date')->get();
    }

    public function find(string $id): ?JournalEntry
    {
        return JournalEntry::with('lines')->find($id);
    }

    public function create(array $data): JournalEntry
    {
        return JournalEntry::create($data);
    }

    public function update(JournalEntry $entry, array $data): bool
    {
        return $entry->update($data);
    }

    public function delete(JournalEntry $entry): bool
    {
        return $entry->delete();
    }
} 
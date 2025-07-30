<?php

namespace App\Repositories\Interfaces;

use App\Models\JournalEntry;
use Illuminate\Database\Eloquent\Collection;

interface JournalEntryRepositoryInterface
{
    public function all(): Collection;
    public function find(string $id): ?JournalEntry;
    public function create(array $data): JournalEntry;
    public function update(JournalEntry $entry, array $data): bool;
    public function delete(JournalEntry $entry): bool;
} 
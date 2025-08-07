<?php

namespace App\Services\AccountancyJournalEntry;

use App\Repositories\AccountancyJournalEntry\AccountancyJournalEntryRepositoryInterface;
use App\Models\AccountancyJournalEntry;
use App\Models\AccountancyJournalEntryLine;
use App\Enums\JournalEntryStatus;
use Illuminate\Support\Str;

class JournalEntryService
{
    public function __construct(
        private AccountancyJournalEntryRepositoryInterface $repository
    ) {}

    public function create(array $data): AccountancyJournalEntry
    {
        // Determine company ID from the first line's chart of account
        $firstLine = $data['lines'][0] ?? null;
        if ($firstLine && isset($firstLine['chart_of_account_id'])) {
            $chartOfAccount = \App\Models\AccountancyChartOfAccount::find($firstLine['chart_of_account_id']);
            if ($chartOfAccount) {
                $data['accountancy_company_id'] = $chartOfAccount->accountancy_company_id;
            }
        }

        // Generate journal number if not provided
        if (empty($data['journal_number'])) {
            $data['journal_number'] = AccountancyJournalEntry::generateJournalNumber();
        }

        // Set default status
        $data['status'] = $data['status'] ?? JournalEntryStatus::DRAFT;

        // Create journal entry
        $journalEntry = $this->repository->create($data);

        // Create journal entry lines
        if (isset($data['lines']) && is_array($data['lines'])) {
            foreach ($data['lines'] as $lineData) {
                $lineData['journal_entry_id'] = $journalEntry->id;
                $lineData['id'] = (string) Str::uuid();
                
                AccountancyJournalEntryLine::create($lineData);
            }
        }

        return $journalEntry->load('accountancyJournalEntryLines');
    }

    public function update(AccountancyJournalEntry $journalEntry, array $data): AccountancyJournalEntry
    {
        // Update journal entry
        $this->repository->update($journalEntry, $data);

        // Update journal entry lines if provided
        if (isset($data['lines']) && is_array($data['lines'])) {
            // Delete existing lines
            $journalEntry->accountancyJournalEntryLines()->delete();

            // Create new lines
            foreach ($data['lines'] as $lineData) {
                $lineData['journal_entry_id'] = $journalEntry->id;
                $lineData['id'] = (string) Str::uuid();
                
                AccountancyJournalEntryLine::create($lineData);
            }
        }

        return $journalEntry->load('accountancyJournalEntryLines');
    }

    public function post(AccountancyJournalEntry $journalEntry): void
    {
        if ($journalEntry->isPosted()) {
            throw new \Exception('Jurnal sudah diposting.');
        }

        // Update status to posted
        $this->repository->update($journalEntry, [
            'status' => JournalEntryStatus::POSTED
        ]);

        // Add to history
        $this->addToHistory($journalEntry, 'posted', 'Jurnal diposting');
    }

    private function addToHistory(AccountancyJournalEntry $journalEntry, string $action, string $description): void
    {
        $history = $journalEntry->history ?? [];
        
        $history[] = [
            'action' => $action,
            'description' => $description,
            'user' => auth()->user()->name ?? 'System',
            'at' => now()->toDateTimeString(),
            'changes' => []
        ];

        $this->repository->update($journalEntry, ['history' => $history]);
    }
}

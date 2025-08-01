<?php

namespace App\Exports;

use App\Models\AccountancyJournalEntry;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class JournalEntriesExport implements FromCollection, WithHeadings, WithMapping
{
    private $dateFrom;
    private $dateTo;
    // public $fileName = 'journal_entries.xlsx';

    public function __construct($dateFrom = null, $dateTo = null)
    {
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        $query = AccountancyJournalEntry::with('accountancyJournalEntryLines.accountancyChartOfAccount');
        if ($this->dateFrom) {
            $query->where('date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->where('date', '<=', $this->dateTo);
        }
        return $query->orderBy('date', 'desc')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Referensi', 'Deskripsi', 'Akun', 'Debit', 'Kredit', 'Line Deskripsi'
        ];
    }

    public function map($entry): array
    {
        $rows = [];
        foreach ($entry->accountancyJournalEntryLines as $line) {
            $rows[] = [
                $entry->date,
                $entry->reference,
                $entry->description,
                $line->accountancyChartOfAccount ? ($line->accountancyChartOfAccount->code . ' - ' . $line->accountancyChartOfAccount->name) : '',
                $line->debit,
                $line->credit,
                $line->description,
            ];
        }
        // Only the first row will be used by Excel, so we flatten for DataTables export
        return $rows[0];
    }
}

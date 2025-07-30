<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class GeneralLedgerExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        return $this->data['lines'];
    }
    public function headings(): array
    {
        return ['Tanggal', 'No Jurnal', 'Referensi', 'Deskripsi', 'Debit', 'Kredit', 'Saldo Berjalan'];
    }
    public function map($line): array
    {
        static $saldo = null;
        if ($saldo === null) {
            $saldo = $this->data['opening'];
        }
        $saldo += $line->debit - $line->credit;
        return [
            $line->journalEntry->date,
            $line->journalEntry->journal_number,
            $line->journalEntry->reference,
            $line->description,
            $line->debit,
            $line->credit,
            $saldo,
        ];
    }
} 
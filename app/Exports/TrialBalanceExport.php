<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TrialBalanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        return $this->data['rows'];
    }
    public function headings(): array
    {
        return ['Kode', 'Nama', 'Saldo Awal', 'Mutasi Debit', 'Mutasi Kredit', 'Saldo Akhir'];
    }
    public function map($row): array
    {
        return [
            $row->account->code,
            $row->account->name,
            $row->opening,
            $row->mutasi_debit,
            $row->mutasi_kredit,
            $row->closing,
        ];
    }
} 
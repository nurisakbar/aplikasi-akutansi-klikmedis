<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Collection;

class BalanceSheetExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    public function collection()
    {
        $rows = collect();
        foreach ($this->data['rows'] as $section) {
            foreach ($section->accounts as $row) {
                $rows->push((object) [
                    'kategori' => ucfirst($section->type),
                    'kode' => $row->account->code,
                    'nama' => $row->account->name,
                    'saldo' => $row->saldo,
                ]);
            }
        }
        return $rows;
    }
    public function headings(): array
    {
        return ['Kategori', 'Kode', 'Nama', 'Saldo'];
    }
    public function map($row): array
    {
        return [
            $row->kategori,
            $row->kode,
            $row->nama,
            $row->saldo,
        ];
    }
} 
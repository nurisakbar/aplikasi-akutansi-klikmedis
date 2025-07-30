<?php

namespace App\Exports;

use App\Repositories\Interfaces\TaxRepositoryInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TaxExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;
    protected $repository;

    public function __construct($filter)
    {
        $this->filter = $filter;
        $this->repository = app(TaxRepositoryInterface::class);
    }

    public function collection()
    {
        return $this->repository->filter($this->filter);
    }

    public function headings(): array
    {
        return [
            'Jenis Pajak', 'No. Dokumen', 'Tanggal', 'Nominal', 'Status', 'Deskripsi',
        ];
    }

    public function map($row): array
    {
        return [
            $row->type,
            $row->document_number,
            $row->date,
            $row->amount,
            $row->status,
            $row->description,
        ];
    }
} 
<?php

namespace App\Exports;

use App\Repositories\Interfaces\FixedAssetRepositoryInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class FixedAssetExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;
    protected $repository;

    public function __construct($filter)
    {
        $this->filter = $filter;
        $this->repository = app(FixedAssetRepositoryInterface::class);
    }

    public function collection()
    {
        return $this->repository->filter($this->filter);
    }

    public function headings(): array
    {
        return [
            'Kode', 'Nama', 'Kategori', 'Tgl Perolehan', 'Nilai Perolehan', 'Umur', 'Metode', 'Nilai Residu', 'Deskripsi',
        ];
    }

    public function map($row): array
    {
        return [
            $row->code,
            $row->name,
            $row->category,
            $row->acquisition_date,
            $row->acquisition_value,
            $row->useful_life,
            $row->depreciation_method,
            $row->residual_value,
            $row->description,
        ];
    }
} 
<?php

namespace App\Exports;

use App\Repositories\Interfaces\CashBankRepositoryInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CashBankExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;
    protected $repository;

    public function __construct($filter)
    {
        $this->filter = $filter;
        $this->repository = app(CashBankRepositoryInterface::class);
    }

    public function collection()
    {
        return $this->repository->getTransactions(
            $this->filter['account_id'] ?? null,
            $this->filter['date_from'] ?? null,
            $this->filter['date_to'] ?? null,
            $this->filter['type'] ?? null,
            $this->filter['status'] ?? null
        );
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Akun', 'Tipe', 'Deskripsi', 'Status', 'Nominal',
        ];
    }

    public function map($row): array
    {
        return [
            $row->date,
            optional($row->account)->name,
            $row->type,
            $row->description,
            $row->status,
            $row->amount,
        ];
    }
} 
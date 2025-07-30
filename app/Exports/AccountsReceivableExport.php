<?php

namespace App\Exports;

use App\Repositories\Interfaces\AccountsReceivableRepositoryInterface;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AccountsReceivableExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filter;
    protected $repository;

    public function __construct($filter)
    {
        $this->filter = $filter;
        $this->repository = app(AccountsReceivableRepositoryInterface::class);
    }

    public function collection()
    {
        return $this->repository->getReceivables(
            $this->filter['customer_id'] ?? null,
            $this->filter['date_from'] ?? null,
            $this->filter['date_to'] ?? null,
            $this->filter['status'] ?? null
        );
    }

    public function headings(): array
    {
        return [
            'Tanggal', 'Jatuh Tempo', 'Customer', 'Status', 'Nominal', 'Deskripsi',
        ];
    }

    public function map($row): array
    {
        return [
            $row->date,
            $row->due_date,
            optional($row->customer)->name,
            $row->status,
            $row->amount,
            $row->description,
        ];
    }
} 
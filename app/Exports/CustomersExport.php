<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CustomersExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $customers;

    public function __construct($customers)
    {
        $this->customers = $customers;
    }

    public function collection()
    {
        return $this->customers;
    }

    public function headings(): array
    {
        return [
            'Kode',
            'Nama Customer',
            'Nama Perusahaan',
            'Email',
            'Telepon',
            'Alamat',
            'NPWP',
            'Batas Kredit',
            'Saldo Piutang',
            'Kredit Tersedia',
            'Status',
            'Contact Person',
            'Syarat Pembayaran'
        ];
    }

    public function map($customer): array
    {
        return [
            $customer->code,
            $customer->name,
            $customer->company_name,
            $customer->email,
            $customer->phone,
            $customer->address,
            $customer->npwp,
            number_format($customer->credit_limit, 0, ',', '.'),
            number_format($customer->outstanding_balance, 0, ',', '.'),
            number_format($customer->available_credit, 0, ',', '.'),
            $customer->formatted_status,
            $customer->contact_person,
            $customer->payment_terms
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'E2EFDA']
                ]
            ]
        ];
    }
} 
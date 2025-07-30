<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Expense;
use Illuminate\Support\Str;

class ExpenseSeeder extends Seeder
{
    public function run(): void
    {
        $expenses = [
            [
                'type' => 'Salary Expense',
                'document_number' => 'EXP-2024-001',
                'date' => now()->subDays(1),
                'amount' => 5000000,
                'status' => 'paid',
                'description' => 'Pembayaran gaji karyawan bulan lalu',
            ],
            [
                'type' => 'Rent Expense',
                'document_number' => 'EXP-2024-002',
                'date' => now()->subDays(5),
                'amount' => 2500000,
                'status' => 'unpaid',
                'description' => 'Sewa kantor bulan berjalan',
            ],
            [
                'type' => 'Utilities Expense',
                'document_number' => 'EXP-2024-003',
                'date' => now()->subDays(10),
                'amount' => 750000,
                'status' => 'paid',
                'description' => 'Pembayaran listrik dan air',
            ],
            [
                'type' => 'Office Supplies',
                'document_number' => 'EXP-2024-004',
                'date' => now()->subDays(15),
                'amount' => 300000,
                'status' => 'paid',
                'description' => 'Pembelian alat tulis kantor',
            ],
            [
                'type' => 'Interest Expense',
                'document_number' => 'EXP-2024-005',
                'date' => now()->subDays(20),
                'amount' => 1200000,
                'status' => 'unpaid',
                'description' => 'Beban bunga pinjaman bank',
            ],
            [
                'type' => 'Depreciation Expense',
                'document_number' => 'EXP-2024-006',
                'date' => now()->subDays(25),
                'amount' => 900000,
                'status' => 'paid',
                'description' => 'Penyusutan aset tetap',
            ],
            [
                'type' => 'Maintenance Expense',
                'document_number' => 'EXP-2024-007',
                'date' => now()->subDays(30),
                'amount' => 600000,
                'status' => 'paid',
                'description' => 'Perawatan gedung kantor',
            ],
            [
                'type' => 'Insurance Expense',
                'document_number' => 'EXP-2024-008',
                'date' => now()->subDays(35),
                'amount' => 800000,
                'status' => 'unpaid',
                'description' => 'Premi asuransi tahunan',
            ],
            [
                'type' => 'Travel Expense',
                'document_number' => 'EXP-2024-009',
                'date' => now()->subDays(40),
                'amount' => 1500000,
                'status' => 'paid',
                'description' => 'Perjalanan dinas luar kota',
            ],
            [
                'type' => 'Training Expense',
                'document_number' => 'EXP-2024-010',
                'date' => now()->subDays(45),
                'amount' => 2000000,
                'status' => 'unpaid',
                'description' => 'Pelatihan karyawan baru',
            ],
        ];

        foreach ($expenses as $expense) {
            Expense::create($expense);
        }
    }
} 
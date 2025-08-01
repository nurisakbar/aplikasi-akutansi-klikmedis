<?php

namespace App\Exports;

use App\Models\AccountancyChartOfAccount;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\Auth;

class ChartOfAccountsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        if (!Auth::check() || !Auth::user()->accountancy_company_id) {
            return collect([]);
        }

        return AccountancyChartOfAccount::getByCompanyId(Auth::user()->accountancy_company_id)
            ->with('parent')
            ->orderBy('code')
            ->get();
    }

    public function headings(): array
    {
        return [
            'Kode', 'Nama', 'Tipe', 'Kategori', 'Parent', 'Level', 'Status', 'Deskripsi'
        ];
    }

    public function map($account): array
    {
        return [
            $account->code,
            $account->name,
            ucfirst($account->type),
            ucwords(str_replace('_', ' ', $account->category)),
            $account->parent ? ($account->parent->code . ' - ' . $account->parent->name) : '-',
            $account->level,
            $account->is_active ? 'Aktif' : 'Nonaktif',
            $account->description,
        ];
    }
}

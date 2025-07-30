<?php

namespace App\Services;

use App\Repositories\Interfaces\BalanceSheetRepositoryInterface;

class BalanceSheetService
{
    protected $repository;
    public function __construct(BalanceSheetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function getBalanceSheet(?string $dateTo = null, ?string $status = 'posted')
    {
        $rows = $this->repository->getBalanceSheetData($dateTo, $status);
        $aset = $rows->firstWhere('type', 'asset');
        $liabilitas = $rows->firstWhere('type', 'liability');
        $ekuitas = $rows->firstWhere('type', 'equity');
        $totalAset = $aset ? $aset->total : 0;
        $totalLiabilitas = $liabilitas ? $liabilitas->total : 0;
        $totalEkuitas = $ekuitas ? $ekuitas->total : 0;
        $selisih = $totalAset - ($totalLiabilitas + $totalEkuitas);
        return [
            'rows' => $rows,
            'total_aset' => $totalAset,
            'total_liabilitas' => $totalLiabilitas,
            'total_ekuitas' => $totalEkuitas,
            'selisih' => $selisih,
        ];
    }
} 
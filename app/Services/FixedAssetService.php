<?php

namespace App\Services;

use App\Repositories\Interfaces\FixedAssetRepositoryInterface;
use App\Models\FixedAsset;
use Illuminate\Support\Facades\DB;

class FixedAssetService
{
    protected $repository;
    public function __construct(FixedAssetRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    public function update(string $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->repository->update($id, $data);
        });
    }

    public function delete(string $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->repository->delete($id);
        });
    }

    public function getList(array $filter = [])
    {
        return $this->repository->filter($filter);
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function calculateDepreciation(FixedAsset $asset, $year = null)
    {
        $method = $asset->depreciation_method;
        $acq = $asset->acquisition_value;
        $res = $asset->residual_value;
        $life = $asset->useful_life;
        if ($method === 'straight_line') {
            $expense = ($acq - $res) / $life;
            return [
                'method' => 'Garis Lurus',
                'annual_expense' => $expense,
                'total_years' => $life,
                'schedule' => collect(range(1, $life))->map(function($y) use ($expense, $acq, $res, $life) {
                    return [
                        'year' => $y,
                        'expense' => $expense,
                        'accumulated' => $expense * $y,
                        'book_value' => max($acq - ($expense * $y), $res),
                    ];
                })->toArray(),
            ];
        } elseif ($method === 'declining') {
            $rate = 2 / $life;
            $schedule = [];
            $book = $acq;
            for ($y = 1; $y <= $life; $y++) {
                $expense = $book * $rate;
                if ($book - $expense < $res) $expense = $book - $res;
                $book -= $expense;
                $schedule[] = [
                    'year' => $y,
                    'expense' => $expense,
                    'accumulated' => $acq - $book,
                    'book_value' => $book,
                ];
                if ($book <= $res) break;
            }
            return [
                'method' => 'Saldo Menurun',
                'rate' => $rate,
                'total_years' => $life,
                'schedule' => $schedule,
            ];
        }
        return null;
    }
} 
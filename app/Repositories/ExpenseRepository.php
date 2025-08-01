<?php

namespace App\Repositories;

use App\Repositories\Interfaces\ExpenseRepositoryInterface;
use App\Models\AccountancyExpense;
use Illuminate\Support\Collection;

class ExpenseRepository implements ExpenseRepositoryInterface
{
    public function all(): Collection
    {
        return AccountancyExpense::orderBy('date', 'desc')->get();
    }

    public function filter(array $filter): Collection
    {
        $query = AccountancyExpense::query();
        if (!empty($filter['type'])) {
            $type = trim($filter['type']);
            if ($type !== '') {
                $query->where('type', $type);
            }
        }
        if (!empty($filter['status'])) $query->where('status', $filter['status']);
        if (!empty($filter['date_from'])) $query->where('date', '>=', $filter['date_from']);
        if (!empty($filter['date_to'])) $query->where('date', '<=', $filter['date_to']);
        return $query->orderBy('date', 'desc')->get();
    }

    public function find(string $id)
    {
        return AccountancyExpense::find($id);
    }

    public function create(array $data)
    {
        return AccountancyExpense::create($data);
    }

    public function update(string $id, array $data)
    {
        $expense = AccountancyExpense::findOrFail($id);
        $expense->update($data);
        return $expense;
    }

    public function delete(string $id)
    {
        $expense = AccountancyExpense::findOrFail($id);
        return $expense->delete();
    }
}

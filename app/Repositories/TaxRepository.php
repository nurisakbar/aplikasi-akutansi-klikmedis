<?php

namespace App\Repositories;

use App\Repositories\Interfaces\TaxRepositoryInterface;
use App\Models\Tax;
use Illuminate\Support\Collection;

class TaxRepository implements TaxRepositoryInterface
{
    public function all(): Collection
    {
        return Tax::orderBy('date', 'desc')->get();
    }

    public function filter(array $filter): Collection
    {
        $query = Tax::query();
        if (!empty($filter['type'])) $query->where('type', $filter['type']);
        if (!empty($filter['status'])) $query->where('status', $filter['status']);
        if (!empty($filter['date_from'])) $query->where('date', '>=', $filter['date_from']);
        if (!empty($filter['date_to'])) $query->where('date', '<=', $filter['date_to']);
        return $query->orderBy('date', 'desc')->get();
    }

    public function find(string $id)
    {
        return Tax::find($id);
    }

    public function create(array $data)
    {
        return Tax::create($data);
    }

    public function update(string $id, array $data)
    {
        $tax = Tax::findOrFail($id);
        $tax->update($data);
        return $tax;
    }

    public function delete(string $id)
    {
        $tax = Tax::findOrFail($id);
        return $tax->delete();
    }
} 
<?php

namespace App\Repositories;

use App\Repositories\Interfaces\FixedAssetRepositoryInterface;
use App\Models\AccountancyFixedAsset;
use Illuminate\Support\Collection;

class FixedAssetRepository implements FixedAssetRepositoryInterface
{
    public function all(): Collection
    {
        return AccountancyFixedAsset::orderBy('acquisition_date', 'desc')->get();
    }

    public function filter(array $filter): Collection
    {
        $query = AccountancyFixedAsset::query();
        if (!empty($filter['category'])) $query->where('category', $filter['category']);
        if (!empty($filter['name'])) $query->where('name', 'like', '%'.$filter['name'].'%');
        if (!empty($filter['acquisition_date_from'])) $query->where('acquisition_date', '>=', $filter['acquisition_date_from']);
        if (!empty($filter['acquisition_date_to'])) $query->where('acquisition_date', '<=', $filter['acquisition_date_to']);
        return $query->orderBy('acquisition_date', 'desc')->get();
    }

    public function find(string $id)
    {
        return AccountancyFixedAsset::find($id);
    }

    public function create(array $data)
    {
        return AccountancyFixedAsset::create($data);
    }

    public function update(string $id, array $data)
    {
        $asset = AccountancyFixedAsset::findOrFail($id);
        $asset->update($data);
        return $asset;
    }

    public function delete(string $id)
    {
        $asset = AccountancyFixedAsset::findOrFail($id);
        return $asset->delete();
    }
}

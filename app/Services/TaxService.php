<?php

namespace App\Services;

use App\Repositories\Interfaces\TaxRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TaxService
{
    protected $repository;
    public function __construct(TaxRepositoryInterface $repository)
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
} 
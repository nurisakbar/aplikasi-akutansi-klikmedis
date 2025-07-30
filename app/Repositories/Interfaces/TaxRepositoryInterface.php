<?php

namespace App\Repositories\Interfaces;

use Illuminate\Support\Collection;

interface TaxRepositoryInterface
{
    public function all(): Collection;
    public function filter(array $filter): Collection;
    public function find(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id);
} 
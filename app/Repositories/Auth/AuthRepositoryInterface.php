<?php

namespace App\Repositories\Auth;

interface AuthRepositoryInterface
{
    public function createUser(array $data);
    public function findUserByEmail(string $email);
    public function createCompany(array $data);
}

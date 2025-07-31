<?php

namespace App\Services\Auth;

interface AuthServiceInterface
{
    public function registerCompany(array $companyData, array $userData);
    public function login(array $credentials);
    public function logout();
}

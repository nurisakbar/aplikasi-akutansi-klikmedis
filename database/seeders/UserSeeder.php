<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        if (!\App\Models\User::where('email', 'admin@example.com')->exists()) {
            \App\Models\User::create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]);
        }
    }
} 
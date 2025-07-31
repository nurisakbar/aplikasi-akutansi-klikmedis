<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Company::create([
            'name' => 'PT KlikMedis Indonesia',
            'address' => 'Jl. Sudirman No. 123, Jakarta Pusat, DKI Jakarta',
            'email' => 'info@klikmedis.com',
            'phone' => '+62-21-1234567',
        ]);

        Company::create([
            'name' => 'CV Maju Bersama',
            'address' => 'Jl. Gatot Subroto No. 456, Jakarta Selatan, DKI Jakarta',
            'email' => 'contact@majubersama.com',
            'phone' => '+62-21-7654321',
        ]);

        Company::create([
            'name' => 'UD Sukses Mandiri',
            'address' => 'Jl. Thamrin No. 789, Jakarta Pusat, DKI Jakarta',
            'email' => 'admin@suksesmandiri.com',
            'phone' => '+62-21-9876543',
        ]);
    }
}

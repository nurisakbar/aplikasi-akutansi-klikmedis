<?php

namespace Database\Seeders;

use App\Models\AccountancyCustomer;
use App\Models\AccountancyCompany;
use App\Enums\CustomerStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountancyCustomerSeeder extends Seeder
{
    public function run(): void
    {
        $companies = AccountancyCompany::all();
        
        // Data customer yang lebih realistis
        $customerData = [
            [
                'name' => 'PT. Medika Sejahtera',
                'company_name' => 'PT. Medika Sejahtera',
                'email' => 'info@medikasejahtera.com',
                'phone' => '021-5550123',
                'address' => 'Jl. Sudirman No. 123, Jakarta Pusat',
                'npwp' => '12.345.678.9-123.456',
                'credit_limit' => 500000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Budi Santoso',
                'payment_terms' => 'Net 30'
            ],
            [
                'name' => 'RS. Kesehatan Prima',
                'company_name' => 'RS. Kesehatan Prima',
                'email' => 'admin@rsprima.com',
                'phone' => '021-5550456',
                'address' => 'Jl. Thamrin No. 45, Jakarta Pusat',
                'npwp' => '23.456.789.0-234.567',
                'credit_limit' => 750000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Siti Rahma',
                'payment_terms' => 'Net 60'
            ],
            [
                'name' => 'Klinik Sehat Bersama',
                'company_name' => 'Klinik Sehat Bersama',
                'email' => 'contact@kliniksehat.com',
                'phone' => '021-5550789',
                'address' => 'Jl. Gatot Subroto No. 67, Jakarta Selatan',
                'npwp' => '34.567.890.1-345.678',
                'credit_limit' => 250000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Ahmad Rizki',
                'payment_terms' => 'Net 30'
            ],
            [
                'name' => 'PT. Farmasi Maju',
                'company_name' => 'PT. Farmasi Maju',
                'email' => 'sales@farmasimaju.com',
                'phone' => '021-5550321',
                'address' => 'Jl. Hayam Wuruk No. 89, Jakarta Barat',
                'npwp' => '45.678.901.2-456.789',
                'credit_limit' => 1000000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Dewi Sartika',
                'payment_terms' => 'Net 90'
            ],
            [
                'name' => 'Laboratorium Medis Pro',
                'company_name' => 'Laboratorium Medis Pro',
                'email' => 'info@labmedispro.com',
                'phone' => '021-5550654',
                'address' => 'Jl. Senayan No. 12, Jakarta Selatan',
                'npwp' => '56.789.012.3-567.890',
                'credit_limit' => 300000000,
                'status' => CustomerStatus::ON_HOLD,
                'contact_person' => 'Rudi Hartono',
                'payment_terms' => 'Net 30'
            ],
            [
                'name' => 'Apotek Sejahtera',
                'company_name' => 'Apotek Sejahtera',
                'email' => 'admin@apoteksejahtera.com',
                'phone' => '021-5550987',
                'address' => 'Jl. Mangga Dua No. 34, Jakarta Utara',
                'npwp' => '67.890.123.4-678.901',
                'credit_limit' => 150000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Maya Indah',
                'payment_terms' => 'Cash'
            ],
            [
                'name' => 'PT. Alat Kesehatan Modern',
                'company_name' => 'PT. Alat Kesehatan Modern',
                'email' => 'sales@alkesmodern.com',
                'phone' => '021-5550432',
                'address' => 'Jl. Kuningan No. 56, Jakarta Selatan',
                'npwp' => '78.901.234.5-789.012',
                'credit_limit' => 800000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Hendra Wijaya',
                'payment_terms' => 'Net 60'
            ],
            [
                'name' => 'Klinik Gigi Senyum',
                'company_name' => 'Klinik Gigi Senyum',
                'email' => 'info@klinikgigisenyum.com',
                'phone' => '021-5550765',
                'address' => 'Jl. Kebayoran Lama No. 78, Jakarta Selatan',
                'npwp' => '89.012.345.6-890.123',
                'credit_limit' => 100000000,
                'status' => CustomerStatus::INACTIVE,
                'contact_person' => 'Dr. Sarah Amanda',
                'payment_terms' => 'Net 30'
            ],
            [
                'name' => 'RS. Ibu dan Anak',
                'company_name' => 'RS. Ibu dan Anak',
                'email' => 'admin@rsibuandanak.com',
                'phone' => '021-5550210',
                'address' => 'Jl. Ciputat Raya No. 90, Jakarta Selatan',
                'npwp' => '90.123.456.7-901.234',
                'credit_limit' => 600000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Nina Safitri',
                'payment_terms' => 'Net 60'
            ],
            [
                'name' => 'PT. Distributor Medis',
                'company_name' => 'PT. Distributor Medis',
                'email' => 'sales@distributormedis.com',
                'phone' => '021-5550543',
                'address' => 'Jl. Pasar Minggu No. 11, Jakarta Selatan',
                'npwp' => '01.234.567.8-012.345',
                'credit_limit' => 1200000000,
                'status' => CustomerStatus::ACTIVE,
                'contact_person' => 'Bambang Sutejo',
                'payment_terms' => 'Net 90'
            ]
        ];
        
        foreach ($companies as $company) {
            foreach ($customerData as $index => $data) {
                $data['accountancy_company_id'] = $company->id;
                $data['name'] = $data['name'] . ' - ' . $company->name;
                $data['email'] = str_replace('@', '@' . strtolower(str_replace(' ', '', $company->name)) . '.', $data['email']);
                
                AccountancyCustomer::create($data);
            }
        }
    }
}

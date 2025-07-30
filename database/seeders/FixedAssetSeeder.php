<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FixedAsset;

class FixedAssetSeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Kendaraan', 'Peralatan', 'Gedung', 'Tanah', 'Elektronik', 'Furnitur'];
        $methods = ['straight_line', 'declining'];
        for ($i = 1; $i <= 30; $i++) {
            FixedAsset::create([
                'code' => 'FA-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'name' => 'Aset Tetap ' . $i,
                'category' => $categories[array_rand($categories)],
                'acquisition_date' => now()->subDays(rand(0, 1000)),
                'acquisition_value' => rand(5000000, 100000000),
                'useful_life' => rand(3, 10),
                'depreciation_method' => $methods[array_rand($methods)],
                'residual_value' => rand(100000, 2000000),
                'description' => 'Aset tetap dummy #' . $i,
            ]);
        }
    }
} 
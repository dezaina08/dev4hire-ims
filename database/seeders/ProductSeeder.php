<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (range(1, 1000) as $index) {
            Product::factory()
            ->create([
                'product_code' => IdGenerator::generate([
                    'table' => 'products',
                    'field' => 'product_code',
                    'length' => 8,
                    'prefix' => 'PC-'
                ]),
            ]);
        }
    }
}

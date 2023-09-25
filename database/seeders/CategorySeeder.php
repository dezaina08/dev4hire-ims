<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Database\Seeder;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Category::factory(25)->create()->each(function($category) {
            Subcategory::factory(25)->create([
                'category_id' => $category->id,
            ])->each(function($subcategory) use ($category) {
                foreach (range(1, 2) as $index) {
                    Product::factory()
                    ->create([
                        'product_code' => IdGenerator::generate([
                            'table' => 'products',
                            'field' => 'product_code',
                            'length' => 8,
                            'prefix' => 'PC-'
                        ]),
                        'category_id' => $category->id,
                        'subcategory_id' => $subcategory->id,
                    ]);
                }
            });
        });
    }
}

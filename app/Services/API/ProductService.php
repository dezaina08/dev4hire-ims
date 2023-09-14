<?php

namespace App\Services\API;

use App\Models\Product;

class ProductService
{
    public function __construct()
    {
    }

    // Cities and Municipalities
    public static function getProducts($category_id)
    {
        $products = Product::where('category_id', $category_id)
            ->select(
                'id',
                'name',
            )
            ->orderBy('name', 'asc')
            ->get();

        return $products;
    }
}

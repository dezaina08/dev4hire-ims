<?php

namespace App\Services\API;

use App\Models\Category;

class CategoryService
{
    public function __construct()
    {
    }

    public static function searchCategory($search)
    {
        $products = Category::where('name', 'like', '%' . $search . '%')
            ->select(
                'id',
                'name',
            )
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return $products;
    }
}

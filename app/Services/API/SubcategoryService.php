<?php

namespace App\Services\API;

use App\Models\Subcategory;

class SubcategoryService
{
    public function __construct()
    {
    }

    public static function searchSubcategory($category_id, $search)
    {
        $products = Subcategory::where('name', 'like', '%' . $search . '%')
            ->where('category_id', $category_id)
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

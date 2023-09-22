<?php

namespace App\Services\API;

use App\Models\Product;
use App\Models\Supplier;

class SupplierService
{
    public function __construct()
    {
    }

    public static function searchSuppliers($search)
    {
        $suppliers = Supplier::where('name', 'like', '%' . $search . '%')
            ->select(
                'id',
                'name',
            )
            ->orderBy('name', 'asc')
            ->limit(10)
            ->get();

        return $suppliers;
    }
}

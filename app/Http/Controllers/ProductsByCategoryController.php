<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class ProductsByCategoryController extends Controller
{
    private $tableName = 'products';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return [
            'response' => $this->getData($request),
            'search' => $request->search ?? '',
            'order' => [
                'orderBy' => $request->orderBy ?? 'id',
                'orderType' => $request->orderType ?? 'desc'
            ]
        ];
    }

    /**
     * Get data from database
     */
    private function getData($request)
    {
        $query = Product::with('category', 'subcategory', 'unit')

        ->orderBy($this->tableName . '.' . ($request->orderBy ?? 'id'), $request->orderType ?? 'desc')

        ->where('category_id', $request->model_id)

        ->when($request->search != '', function ($query) use ($request) {
            return $query->where(function (Builder $groupQuery) use ($request) {
                $groupQuery
                    ->where($this->tableName . '.id', 'like', '%' . $request->search . '%')
                    ->orWhere($this->tableName . '.name', 'like', '%' . $request->search . '%')
                    ->orWhere($this->tableName . '.product_code', 'like', '%' . $request->search . '%');
            });
        })

        ->paginate($request->perPage ?? 10);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(StoreProductByCategoryRequest $request)
    // {
    //     $validated = $request->validated();
    //     DB::beginTransaction();
    //     try {
    //         Product::create($validated);
    //         DB::commit();
    //         return back();
    //     } catch (Throwable $e) {
    //         DB::rollBack();
    //         return $e;
    //     }
    // }

    /**
     * Display the specified resource.
     */
    // public function show(Product $subcategory)
    // {
    //     //
    // }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(Product $subcategory)
    // {
    //     return Inertia::render('Product/Edit', [
    //         'model' => $subcategory
    //     ]);
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateProductRequest $request, Product $subcategory)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(!empty($request->id_array) && is_array($request->id_array)) {
            DB::beginTransaction();
            try {
                Product::destroy($request->id_array);
                DB::commit();
                return back();
            } catch (Throwable $e) {
                DB::rollBack();
                return $e;
            }
        }
    }
}

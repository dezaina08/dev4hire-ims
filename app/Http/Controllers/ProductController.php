<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    private $tableName = 'products';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categoryFilter = Category::find($request->categoryId);
        $subcategoryFilter = Subcategory::find($request->subcategoryId);

        return Inertia::render('Product/Index', [
            'response' => $this->getData($request),
            'search' => $request->search ?? '',
            'order' => [
                'orderBy' => $request->orderBy ?? 'id',
                'orderType' => $request->orderType ?? 'desc'
            ],
            'dateFrom' => $request->dateFrom ?? '',
            'dateUntil' => $request->dateUntil ?? '',
            'category' => $categoryFilter ?? [],
            'subcategory' => $subcategoryFilter ?? [],
        ]);
    }

    /**
     * Get data from database
     */
    private function getData($request)
    {
        // Eager load
        $query = Product::with('category', 'subcategory', 'unit', 'media')
        // Order/Sort
        ->orderBy($this->tableName . '.' . ($request->orderBy ?? 'id'), $request->orderType ?? 'desc')
        // Search
        ->when($request->search != '', function ($query) use ($request) {
            return $query->where(function ($groupQuery) use ($request) {
                $groupQuery
                    ->where($this->tableName . '.id', 'like', '%' . $request->search . '%')
                    ->orWhere($this->tableName . '.name', 'like', '%' . $request->search . '%')
                    ->orWhere($this->tableName . '.product_code', 'like', '%' . $request->search . '%')
                    ->orWhereHas('category', function ($categoryQuery) use ($request) {
                        $categoryQuery->where('name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('subcategory', function ($subcategoryQuery) use ($request) {
                        $subcategoryQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        })
        ->when($request->categoryId != '', function ($query) use ($request) {
            return $query->where($this->tableName . '.category_id', $request->categoryId);
        })
        ->when($request->subcategoryId != '', function ($query) use ($request) {
            return $query->where($this->tableName . '.subcategory_id', $request->subcategoryId);
        })
        ->when($request->dateUntil != '', function ($query) use ($request) {
            return $query->where($this->tableName . '.created_at', '<=', $request->dateUntil);
        })
        ->when($request->dateFrom != '', function ($query) use ($request) {
            return $query->where($this->tableName . '.created_at', '>=', $request->dateFrom);
        })
        ->paginate($request->perPage ?? 10);
    
        return $query;
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $units = Unit::all();
        return Inertia::render('Product/Create', [
            'categories' => $categories,
            'units' => $units,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validated = $request->safe()->except(['photo']);
        DB::beginTransaction();
        try {
            $product = Product::create($validated);

            // Add photo
            if ($request->photo) {
                $product->addMedia($request->photo)
                    ->toMediaCollection('product_photos');
            }

            DB::commit();
            return back();
        } catch (Throwable $e) {
            DB::rollBack();
            return $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('category', 'subcategory', 'unit');
        return Inertia::render('Product/Show', [
            'model' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $units = Unit::all();
        return Inertia::render('Product/Edit', [
            'model' => $product,
            'units' => $units,
            'category' => $product->category,
            'subcategory' => $product->subcategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $product->update($validated);

             // Remove photo
            if ($request->remove_photo) {
                $media = $product->getFirstMedia('product_photos');
                if ($media) {
                    $media->delete();
                }
            }

            // Add photo
            if ($request->photo) {
                $product->addMedia($request->photo)
                    ->toMediaCollection('product_photos');
            }
            DB::commit();
            return back();
        } catch (Throwable $e) {
            DB::rollBack();
            return $e;
        }
    }

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

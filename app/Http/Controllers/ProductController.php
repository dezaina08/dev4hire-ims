<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Unit;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\PurchaseItem;
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
        // dd($this->getData($request));
        return Inertia::render('Product/Index', [
            'response' => $this->getData($request),
            'search' => $request->search ?? '',
            'order' => [
                'orderBy' => $request->orderBy ?? 'id',
                'orderType' => $request->orderType ?? 'desc'
            ]
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
                return $query->orWhere($this->tableName . '.name', 'like', '%' . $request->search . '%');
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
        $product->load('category', 'subcategory', 'unit' );
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

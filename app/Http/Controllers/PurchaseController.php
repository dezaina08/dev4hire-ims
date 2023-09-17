<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\Purchase;
use App\Models\Supplier;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePurchaseRequest;
use App\Http\Requests\UpdatePurchaseRequest;

class PurchaseController extends Controller
{
    private $tableName = 'purchases';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Purchase/Index', [
            'response' => $this->getData($request),
            'search' => $request->search ?? '',
            'order' => [
                'orderBy' => $request->orderBy ?? 'id',
                'orderType' => $request->orderType ?? 'desc'
            ],
            'dateFrom' => $request->dateFrom ?? '',
            'dateUntil' => $request->dateUntil ?? '',
            'status' => $request->status ?? ''
        ]);
    }

    /**
     * Get data from database
     */
    private function getData($request)
    {
        // Eager load
        $query = Purchase::with('supplier')
        // Order/Sort
        ->orderBy($this->tableName . '.' . ($request->orderBy ?? 'id'), $request->orderType ?? 'desc')
        // Search
        ->when($request->search != '', function ($query) use ($request) {
                return $query->where($this->tableName . '.purchase_number', 'like', '%' . $request->search . '%');
        })
        ->when($request->dateUntil != '', function ($query) use ($request) {
            return $query->where($this->tableName . '.purchase_date', '<=', $request->dateUntil);
        })
        ->when($request->dateFrom != '', function ($query) use ($request) {
            return $query->where($this->tableName . '.purchase_date', '>=', $request->dateFrom);
        })
        ->when($request->status != '', function ($query) use ($request) {
            return $query->where($this->tableName . '.is_approved', $request->status);
        })

        ->paginate($request->perPage ?? 10);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::orderBy('name', 'asc')->limit(10)->get();
        return Inertia::render('Purchase/Create', [
            'categories' => $categories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePurchaseRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $validated['created_by'] = Auth::user()->id;
            $validated['updated_by'] = Auth::user()->id;
            $purchase = Purchase::create($validated);

            $total = 0;

            // Create purchase details
            foreach ($validated['purchase_items'] as $purchase_item) {
                $purchase_item['purchase_id'] = $purchase->id;

                // Rename id to product_id
                $purchase_item['product_id'] = $purchase_item['id'];
                unset($purchase_item['id']);

                $total += $purchase_item['total'];

                PurchaseItem::create($purchase_item);
            }

            $purchase->total = $total;
            $purchase->save();

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
    public function show(Purchase $purchase)
    {
        $purchase->load(
            'supplier',
            'purchase_items.product'
        );
        return Inertia::render('Purchase/Show', [
            'model' => $purchase,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Purchase $purchase)
    {
        abort(404);
        // return Inertia::render('Purchase/Edit', [
        //     'model' => $purchase,
        // ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePurchaseRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();
        try {
            $purchase->update([
                'is_approved' => 1,
            ]);

            // Add item quantity to product stock
            foreach ($purchase->purchase_items as $purchase_item) {
                $product = Product::findOrFail($purchase_item['product_id']);
                $product->update([
                    'stock' => $product->stock + $purchase_item['quantity'],
                ]);
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
                Purchase::destroy($request->id_array);
                DB::commit();
                return back();
            } catch (Throwable $e) {
                DB::rollBack();
                return $e;
            }
        }
    }
}

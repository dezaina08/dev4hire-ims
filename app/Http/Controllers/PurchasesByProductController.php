<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\PurchaseItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

class PurchasesByProductController extends Controller
{
    private $tableName = 'purchase_items';

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
        $query = PurchaseItem::with(['product', 'purchase.supplier'])
            ->leftJoin('purchases', 'purchase_items.purchase_id', '=', 'purchases.id')
            ->leftJoin('suppliers', 'purchases.supplier_id', '=', 'suppliers.id') // Join the suppliers table
            ->where('product_id', $request->model_id)
            ->when($request->search != '', function ($query) use ($request) {
                $query->where(function ($groupQuery) use ($request) {
                    $groupQuery
                        ->whereHas('purchase', function ($purchaseQuery) use ($request) {
                            $purchaseQuery->where('purchase_number', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('purchase.supplier', function ($supplierQuery) use ($request) {
                            $supplierQuery->where('name', 'like', '%' . $request->search . '%'); // Reference the supplier name correctly
                        })
                        ->orWhereHas('purchase', function ($purchaseQuery) use ($request) {
                            $purchaseQuery->where('purchase_date', 'like', '%' . $request->search . '%');
                        });
                });
            });
    
        // Sort the query based on the orderBy parameter
        if ($request->orderBy === 'purchase_number') {
            $query->orderBy('purchases.purchase_number', $request->orderType ?? 'desc');
        } elseif ($request->orderBy === 'purchase_date') {
            $query->orderBy('purchases.purchase_date', $request->orderType ?? 'desc');
        } elseif ($request->orderBy === 'supplier') {
            $query->orderBy('suppliers.name', $request->orderType ?? 'desc'); // Reference the supplier name correctly
        } else {
            // Default sorting by 'id' or any other column
            $query->orderBy($this->tableName . '.' . ($request->orderBy ?? 'id'), $request->orderType ?? 'desc');
        }
    
        $result = $query->paginate($request->perPage ?? 10);
    
        return $result;
    }
    
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(!empty($request->id_array) && is_array($request->id_array)) {
            DB::beginTransaction();
            try {
                PurchaseItem::destroy($request->id_array);
                DB::commit();
                return back();
            } catch (Throwable $e) {
                DB::rollBack();
                return $e;
            }
        }
    }
}

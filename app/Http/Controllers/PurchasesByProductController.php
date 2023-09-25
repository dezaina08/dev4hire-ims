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
        $query = PurchaseItem::with('product', 'purchase.supplier')

        ->orderBy($this->tableName . '.' . ($request->orderBy ?? 'id'), $request->orderType ?? 'desc')

        ->where('product_id', $request->model_id)

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

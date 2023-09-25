<?php

namespace App\Http\Controllers;

use Throwable;
use Inertia\Inertia;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreSubcategoryRequest;
use App\Http\Requests\UpdateSubcategoryRequest;
use App\Http\Requests\StoreSubcategoryByCategoryRequest;

class SubcategoriesByCategoryController extends Controller
{
    private $tableName = 'subcategories';

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
        $query = Subcategory::orderBy($this->tableName . '.' . ($request->orderBy ?? 'id'), $request->orderType ?? 'desc')

        ->where('category_id', $request->model_id)

        ->when($request->search != '', function ($query) use ($request) {
                return $query->where($this->tableName . '.name', 'like', '%' . $request->search . '%');
        })

        ->paginate($request->perPage ?? 10);

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSubcategoryByCategoryRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            Subcategory::create($validated);
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
    public function show(Subcategory $subcategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subcategory $subcategory)
    {
        return Inertia::render('Subcategory/Edit', [
            'model' => $subcategory
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubcategoryRequest $request, Subcategory $subcategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        if(!empty($request->id_array) && is_array($request->id_array)) {
            DB::beginTransaction();
            try {
                Subcategory::destroy($request->id_array);
                DB::commit();
                return back();
            } catch (Throwable $e) {
                DB::rollBack();
                return $e;
            }
        }
    }
}

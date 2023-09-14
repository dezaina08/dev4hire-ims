<?php

namespace App\Http\Controllers;

use Throwable;
use App\Models\Unit;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;

class UnitController extends Controller
{
    private $tableName = 'units';

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Inertia::render('Unit/Index', [
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
        $query = Unit::orderBy($this->tableName . '.' . ($request->orderBy ?? 'id'), $request->orderType ?? 'desc')

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
        return Inertia::render('Unit/Create', [
            //
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUnitRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            Unit::create($validated);
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
    public function show(Unit $unit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Unit $unit)
    {
        return Inertia::render('Unit/Edit', [
            'model' => $unit
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUnitRequest $request, Unit $unit)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
            $unit->update($validated);
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
                Unit::destroy($request->id_array);
                DB::commit();
                return back();
            } catch (Throwable $e) {
                DB::rollBack();
                return $e;
            }
        }
    }
}

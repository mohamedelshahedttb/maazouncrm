<?php

namespace App\Http\Controllers;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Area::query();
        if ($request->filled('q')) {
            $q = trim($request->string('q'));
            $query->where('name', 'like', "%{$q}%");
        }
        if ($request->filled('status')) {
            if ($request->string('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->string('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }
        $areas = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('areas.index', compact('areas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('areas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name',
            'transportation_fee' => 'required|numeric|min:0',
            'mahr_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);
        Area::create($validated);
        return redirect()->route('areas.index')->with('success', 'تم إنشاء المنطقة');
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Area $area)
    {
        return view('areas.edit', compact('area'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Area $area)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:areas,name,' . $area->id,
            'transportation_fee' => 'required|numeric|min:0',
            'mahr_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);
        $area->update($validated);
        return redirect()->route('areas.index')->with('success', 'تم تحديث المنطقة');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area)
    {
        $area->delete();
        return redirect()->route('areas.index')->with('success', 'تم حذف المنطقة');
    }
}

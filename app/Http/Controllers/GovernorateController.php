<?php

namespace App\Http\Controllers;

use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    public function index(Request $request)
    {
        $query = Governorate::query();
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
        $governorates = $query->orderBy('name')->paginate(15)->withQueryString();
        return view('governorates.index', compact('governorates'));
    }

    public function create()
    {
        return view('governorates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:governorates,name',
            'base_fixed_fee' => 'required|numeric|min:0',
            'added_fees' => 'required|numeric|min:0',
            'mahr_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);
        Governorate::create($validated);
        return redirect()->route('governorates.index')->with('success', 'تم إنشاء المحافظة');
    }

    public function edit(Governorate $governorate)
    {
        return view('governorates.edit', compact('governorate'));
    }

    public function update(Request $request, Governorate $governorate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:governorates,name,' . $governorate->id,
            'base_fixed_fee' => 'required|numeric|min:0',
            'added_fees' => 'required|numeric|min:0',
            'mahr_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);
        $governorate->update($validated);
        return redirect()->route('governorates.index')->with('success', 'تم تحديث المحافظة');
    }

    public function destroy(Governorate $governorate)
    {
        $governorate->delete();
        return redirect()->route('governorates.index')->with('success', 'تم حذف المحافظة');
    }
}




<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::paginate(15);
        return view('services.index', compact('services'));
    }

    public function create()
    {
        return view('services.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:marriage,divorce,notarization,translation,consultation,other',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'duration_minutes' => 'nullable|integer|min:0',
            'requirements' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('services.index')
            ->with('success', 'تم إنشاء الخدمة بنجاح');
    }

    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service)
    {
        return view('services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:marriage,divorce,notarization,translation,consultation,other',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'duration_minutes' => 'nullable|integer|min:0',
            'requirements' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('services.index')
            ->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy(Service $service)
    {
        $service->delete();
        return redirect()->route('services.index')
            ->with('success', 'تم حذف الخدمة بنجاح');
    }
}

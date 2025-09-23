<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceRatePolicy;
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
        $service->load('ratePolicies');
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

        // Rate policies management: only when policies are posted (non-Livewire path)
        if ($request->has('rate_policies')) {
            $keptPolicyIds = [];
            foreach ($request->input('rate_policies') as $policyData) {
                if (!isset($policyData['fixed_fee'])) {
                    continue;
                }
                if (!empty($policyData['id'])) {
                    $policy = ServiceRatePolicy::where('service_id', $service->id)->find($policyData['id']);
                    if ($policy) {
                        $policy->update([
                            'mahr_min' => $policyData['mahr_min'] ?? null,
                            'mahr_max' => $policyData['mahr_max'] ?? null,
                            'fixed_fee' => $policyData['fixed_fee'],
                            'is_active' => isset($policyData['is_active']) ? (bool)$policyData['is_active'] : true,
                        ]);
                        $keptPolicyIds[] = $policy->id;
                    }
                } else {
                    $new = $service->ratePolicies()->create([
                        'mahr_min' => $policyData['mahr_min'] ?? null,
                        'mahr_max' => $policyData['mahr_max'] ?? null,
                        'fixed_fee' => $policyData['fixed_fee'],
                        'is_active' => isset($policyData['is_active']) ? (bool)$policyData['is_active'] : true,
                    ]);
                    $keptPolicyIds[] = $new->id;
                }
            }

            // Delete policies removed from the form
            ServiceRatePolicy::where('service_id', $service->id)
                ->when(!empty($keptPolicyIds), function($q) use ($keptPolicyIds){
                    $q->whereNotIn('id', $keptPolicyIds);
                })
                ->delete();
        }

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

<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    public function index()
    {
        $partners = Partner::paginate(15);
        return view('partners.index', compact('partners'));
    }

    public function create()
    {
        return view('partners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'office_name' => 'nullable|string|max:255',
            'office_address' => 'nullable|string|max:1000',
            'agent_name' => 'nullable|string|max:255',
            'agent_phone' => 'nullable|string|max:20',
            'agent_email' => 'nullable|email|max:255',
            'location_number' => 'nullable|string|max:255',
            'book_number' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'service_scope' => 'required|string|max:1000',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        Partner::create($validated);

        return redirect()->route('partners.index')
            ->with('success', 'تم إنشاء الالشيخ بنجاح');
    }

    public function show(Partner $partner)
    {
        return view('partners.show', compact('partner'));
    }

    public function edit(Partner $partner)
    {
        return view('partners.edit', compact('partner'));
    }

    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'office_name' => 'nullable|string|max:255',
            'office_address' => 'nullable|string|max:1000',
            'agent_name' => 'nullable|string|max:255',
            'agent_phone' => 'nullable|string|max:20',
            'agent_email' => 'nullable|email|max:255',
            'location_number' => 'nullable|string|max:255',
            'book_number' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'license_number' => 'nullable|string|max:255',
            'service_scope' => 'required|string|max:1000',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'commission_rate' => 'nullable|numeric|min:0|max:100',
            'status' => 'required|in:active,inactive,suspended',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $partner->update($validated);

        return redirect()->route('partners.index')
            ->with('success', 'تم تحديث الالشيخ بنجاح');
    }

    public function destroy(Partner $partner)
    {
        $partner->delete();
        return redirect()->route('partners.index')
            ->with('success', 'تم حذف الالشيخ بنجاح');
    }
}

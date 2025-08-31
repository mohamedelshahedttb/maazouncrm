<?php

namespace App\Http\Controllers;

use App\Models\PartnerAssistanceRequest;
use App\Models\Partner;
use Illuminate\Http\Request;

class PartnerAssistanceRequestController extends Controller
{
    public function index()
    {
        $requests = PartnerAssistanceRequest::with(['requestingPartner', 'assistingPartner'])->paginate(15);
        return view('partner-assistance-requests.index', compact('requests'));
    }

    public function create()
    {
        $partners = Partner::all();
        return view('partner-assistance-requests.create', compact('partners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'requesting_partner_id' => 'required|exists:partners,id',
            'assisting_partner_id' => 'required|exists:partners,id',
            'service_type' => 'required|string|max:255',
            'requested_date_time' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:pending,accepted,in_progress,completed,rejected',
            'commission_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        PartnerAssistanceRequest::create($validated);

        return redirect()->route('partner-assistance-requests.index')
            ->with('success', 'تم إنشاء طلب المساعدة بنجاح');
    }

    public function show(PartnerAssistanceRequest $request)
    {
        return view('partner-assistance-requests.show', compact('request'));
    }

    public function edit(PartnerAssistanceRequest $request)
    {
        $partners = Partner::all();
        return view('partner-assistance-requests.edit', compact('request', 'partners'));
    }

    public function update(Request $request, PartnerAssistanceRequest $assistanceRequest)
    {
        $validated = $request->validate([
            'requesting_partner_id' => 'required|exists:partners,id',
            'assisting_partner_id' => 'required|exists:partners,id',
            'service_type' => 'required|string|max:255',
            'requested_date_time' => 'required|date',
            'location' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'status' => 'required|in:pending,accepted,in_progress,completed,rejected',
            'commission_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $assistanceRequest->update($validated);

        return redirect()->route('partner-assistance-requests.index')
            ->with('success', 'تم تحديث طلب المساعدة بنجاح');
    }

    public function destroy(PartnerAssistanceRequest $request)
    {
        $request->delete();
        return redirect()->route('partner-assistance-requests.index')
            ->with('success', 'تم حذف طلب المساعدة بنجاح');
    }
}

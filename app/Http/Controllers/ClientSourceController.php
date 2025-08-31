<?php

namespace App\Http\Controllers;

use App\Models\ClientSource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientSourceController extends Controller
{
    public function index()
    {
        $sources = ClientSource::orderBy('name')->get();
        return view('client-sources.index', compact('sources'));
    }

    public function create()
    {
        $types = ClientSource::getTypes();
        return view('client-sources.create', compact('types'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:client_sources',
            'type' => 'required|string|in:' . implode(',', array_keys(ClientSource::getTypes())),
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        ClientSource::create($request->all());

        return redirect()->route('client-sources.index')
            ->with('success', 'تم إنشاء مصدر العميل بنجاح');
    }

    public function show(ClientSource $clientSource)
    {
        return view('client-sources.show', compact('clientSource'));
    }

    public function edit(ClientSource $clientSource)
    {
        $types = ClientSource::getTypes();
        return view('client-sources.edit', compact('clientSource', 'types'));
    }

    public function update(Request $request, ClientSource $clientSource)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:client_sources,name,' . $clientSource->id,
            'type' => 'required|string|in:' . implode(',', array_keys(ClientSource::getTypes())),
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $clientSource->update($request->all());

        return redirect()->route('client-sources.index')
            ->with('success', 'تم تحديث مصدر العميل بنجاح');
    }

    public function destroy(ClientSource $clientSource)
    {
        // Check if source is being used by any clients
        if ($clientSource->clients()->count() > 0) {
            return redirect()->route('client-sources.index')
                ->with('error', 'لا يمكن حذف هذا المصدر لأنه مستخدم من قبل عملاء');
        }

        $clientSource->delete();

        return redirect()->route('client-sources.index')
            ->with('success', 'تم حذف مصدر العميل بنجاح');
    }
}

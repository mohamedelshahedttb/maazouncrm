<?php

namespace App\Http\Controllers;

use App\Models\SupplierOrder;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierOrderController extends Controller
{
    public function index()
    {
        $orders = SupplierOrder::with('supplier')->paginate(15);
        return view('supplier-orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('supplier-orders.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_number' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expected_delivery_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,in_progress,delivered,cancelled',
            'notes' => 'nullable|string|max:1000',
            'quality_notes' => 'nullable|string|max:1000',
            'delivery_notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        SupplierOrder::create($validated);

        return redirect()->route('supplier-orders.index')
            ->with('success', 'تم إنشاء طلب المورد بنجاح');
    }

    public function show(SupplierOrder $order)
    {
        return view('supplier-orders.show', compact('order'));
    }

    public function edit(SupplierOrder $order)
    {
        $suppliers = Supplier::all();
        return view('supplier-orders.edit', compact('order', 'suppliers'));
    }

    public function update(Request $request, SupplierOrder $order)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_number' => 'required|string|max:255',
            'description' => 'required|string|max:1000',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expected_delivery_date' => 'required|date',
            'status' => 'required|in:pending,confirmed,in_progress,delivered,cancelled',
            'notes' => 'nullable|string|max:1000',
            'quality_notes' => 'nullable|string|max:1000',
            'delivery_notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $order->update($validated);

        return redirect()->route('supplier-orders.index')
            ->with('success', 'تم تحديث طلب المورد بنجاح');
    }

    public function destroy(SupplierOrder $order)
    {
        $order->delete();
        return redirect()->route('supplier-orders.index')
            ->with('success', 'تم حذف طلب المورد بنجاح');
    }
}

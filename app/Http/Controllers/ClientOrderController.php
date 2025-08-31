<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;

class ClientOrderController extends Controller
{
    public function index()
    {
        $orders = ClientOrder::with(['client', 'service', 'appointment'])->paginate(15);
        return view('client-orders.index', compact('orders'));
    }

    public function create()
    {
        $clients = Client::all();
        $services = Service::all();
        $appointments = Appointment::all();
        return view('client-orders.create', compact('clients', 'services', 'appointments'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expected_completion_date' => 'nullable|date',
            'requirements' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'special_instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        ClientOrder::create($validated);

        return redirect()->route('client-orders.index')
            ->with('success', 'تم إنشاء الطلب بنجاح');
    }

    public function show(ClientOrder $order)
    {
        return view('client-orders.show', compact('order'));
    }

    public function edit(ClientOrder $order)
    {
        $clients = Client::all();
        $services = Service::all();
        $appointments = Appointment::all();
        return view('client-orders.edit', compact('order', 'clients', 'services', 'appointments'));
    }

    public function update(Request $request, ClientOrder $order)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'total_amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'expected_completion_date' => 'nullable|date',
            'requirements' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'special_instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $order->update($validated);

        return redirect()->route('client-orders.index')
            ->with('success', 'تم تحديث الطلب بنجاح');
    }

    public function destroy(ClientOrder $order)
    {
        $order->delete();
        return redirect()->route('client-orders.index')
            ->with('success', 'تم حذف الطلب بنجاح');
    }

    public function confirm(ClientOrder $order)
    {
        $order->update(['status' => 'confirmed']);
        return back()->with('success', 'تم تأكيد الطلب');
    }
}

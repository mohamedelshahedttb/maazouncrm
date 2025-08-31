<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'service'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('invoices.index', compact('invoices'));
    }

    public function create(Request $request)
    {
        $clients = Client::active()->get();
        $services = Service::active()->get();
        $appointments = Appointment::confirmed()->get();
        
        $selectedClientId = $request->get('client_id');

        return view('invoices.create', compact('clients', 'services', 'appointments', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'notes' => 'nullable|string|max:1000',
            'payment_terms' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Generate invoice number
            $validated['invoice_number'] = Invoice::generateInvoiceNumber();
            
            // Calculate total
            $validated['total_amount'] = $validated['subtotal'] + 
                ($validated['tax_amount'] ?? 0) - 
                ($validated['discount_amount'] ?? 0);
            
            // Set currency from service
            $service = Service::find($validated['service_id']);
            $validated['currency'] = $service->currency;
            
            // Set default status
            $validated['status'] = Invoice::STATUS_DRAFT;

            $invoice = Invoice::create($validated);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create invoice: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'فشل في إنشاء الفاتورة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'service', 'appointment']);
        
        return view('invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $clients = Client::active()->get();
        $services = Service::active()->get();
        $appointments = Appointment::confirmed()->get();

        return view('invoices.edit', compact('invoice', 'clients', 'services', 'appointments'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after:issue_date',
            'notes' => 'nullable|string|max:1000',
            'payment_terms' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total
            $validated['total_amount'] = $validated['subtotal'] + 
                ($validated['tax_amount'] ?? 0) - 
                ($validated['discount_amount'] ?? 0);
            
            // Set currency from service
            $service = Service::find($validated['service_id']);
            $validated['currency'] = $service->currency;

            $invoice->update($validated);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم تحديث الفاتورة بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update invoice: ' . $e->getMessage());
            
            return back()->withInput()
                ->with('error', 'فشل في تحديث الفاتورة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            
            return redirect()->route('invoices.index')
                ->with('success', 'تم حذف الفاتورة بنجاح');
                
        } catch (\Exception $e) {
            Log::error('Failed to delete invoice: ' . $e->getMessage());
            
            return back()->with('error', 'فشل في حذف الفاتورة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function send(Invoice $invoice)
    {
        if (!$invoice->canBeSent()) {
            return back()->with('error', 'لا يمكن إرسال هذه الفاتورة');
        }

        try {
            $invoice->markAsSent();
            
            // TODO: Send email/WhatsApp notification to client
            
            return back()->with('success', 'تم إرسال الفاتورة بنجاح');
            
        } catch (\Exception $e) {
            Log::error('Failed to send invoice: ' . $e->getMessage());
            
            return back()->with('error', 'فشل في إرسال الفاتورة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function markAsPaid(Request $request, Invoice $invoice)
    {
        if (!$invoice->canBePaid()) {
            return back()->with('error', 'لا يمكن دفع هذه الفاتورة');
        }

        $validated = $request->validate([
            'payment_method' => 'required|string|max:100',
        ]);

        try {
            $invoice->markAsPaid($validated['payment_method']);
            
            return back()->with('success', 'تم تحديث حالة الفاتورة إلى مدفوعة');
            
        } catch (\Exception $e) {
            Log::error('Failed to mark invoice as paid: ' . $e->getMessage());
            
            return back()->with('error', 'فشل في تحديث حالة الفاتورة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function cancel(Invoice $invoice)
    {
        if (!$invoice->canBeCancelled()) {
            return back()->with('error', 'لا يمكن إلغاء هذه الفاتورة');
        }

        try {
            $invoice->markAsCancelled();
            
            return back()->with('success', 'تم إلغاء الفاتورة بنجاح');
            
        } catch (\Exception $e) {
            Log::error('Failed to cancel invoice: ' . $e->getMessage());
            
            return back()->with('error', 'فشل في إلغاء الفاتورة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function generateFromAppointment(Appointment $appointment)
    {
        try {
            // Check if invoice already exists
            if ($appointment->invoices()->exists()) {
                return back()->with('error', 'يوجد فاتورة بالفعل لهذا الموعد');
            }

            DB::beginTransaction();

            $invoice = Invoice::create([
                'invoice_number' => Invoice::generateInvoiceNumber(),
                'client_id' => $appointment->client_id,
                'service_id' => $appointment->service_id,
                'appointment_id' => $appointment->id,
                'subtotal' => $appointment->service->price,
                'tax_amount' => 0,
                'discount_amount' => 0,
                'total_amount' => $appointment->service->price,
                'currency' => $appointment->service->currency,
                'status' => Invoice::STATUS_DRAFT,
                'issue_date' => now(),
                'due_date' => now()->addDays(30),
                'billing_address' => $appointment->client->address,
            ]);

            DB::commit();

            return redirect()->route('invoices.show', $invoice)
                ->with('success', 'تم إنشاء الفاتورة من الموعد بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to generate invoice from appointment: ' . $e->getMessage());
            
            return back()->with('error', 'فشل في إنشاء الفاتورة. يرجى المحاولة مرة أخرى.');
        }
    }

    public function download(Invoice $invoice)
    {
        // TODO: Generate PDF invoice
        return back()->with('info', 'ميزة تحميل الفاتورة قيد التطوير');
    }
}

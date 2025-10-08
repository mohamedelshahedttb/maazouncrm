<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\ClientOrder;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $query = Client::with(['appointments', 'orders']);

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('bride_name', 'like', "%{$search}%");
            });
        }

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('client_status', $request->get('status'));
        }

        $clients = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('clients.index', compact('clients'));
    }

    public function create()
    {
        $services = Service::where('is_active', true)->get();
        $sources = \App\Models\ClientSource::where('is_active', true)->get();
        $products = \App\Models\Product::where('is_active', true)->where('status', 'active')->get();
        return view('clients.create', compact('services', 'sources', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'geographical_area' => 'nullable|string|max:255',
            'client_status' => 'required|in:new,in_progress,completed,cancelled',
            'call_result' => 'nullable|in:interested,not_interested,follow_up_later,potential_client,confirmed_booking,completed_booking,cancelled,inquiry,client_booking,no_answer,busy_number',
            'next_follow_up_date' => 'nullable|string',
            'relationship_status' => 'nullable|string|max:255',
            'bride_age' => 'nullable|integer|min:1|max:100',
            'accessories' => 'nullable|array',
            'accessories.*' => 'exists:products,id',
            'event_date' => 'nullable|string',
            'contract_location' => 'nullable|string|max:255',
            'contract_cost' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed_amount',
            'discount_value' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'contract_address' => 'nullable|string|max:1000',
            'mahr' => 'nullable|string|max:255',
            'bride_id_address' => 'nullable|string|max:1000',
            'contract_delivery_method' => 'nullable|in:delivery,office',
            'contract_delivery_date' => 'nullable|string',
            'temporary_document' => 'nullable|string|max:255',
            'sheikh_name' => 'nullable|string|max:255',
            'book_number' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'coupon_arrival_date' => 'nullable|string',
            'document_receipt_date' => 'nullable|string',
            'document_receiver' => 'nullable|in:delivery,client,client_relative',
            'delivery_man_name' => 'nullable|string|max:255',
            'client_relative_name' => 'nullable|string|max:255',
            'google_maps_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
            'whatsapp_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'service_id' => 'nullable|exists:services,id',
            'source_id' => 'nullable|exists:client_sources,id',
            'area_id' => 'nullable|exists:areas,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'client_status' => 'nullable|in:new,in_progress,completed,cancelled',
        ]);

        // Convert date fields from dd/mm/yyyy to yyyy-mm-dd
        $dateFields = ['event_date', 'next_follow_up_date', 'coupon_arrival_date', 'document_receipt_date'];
        foreach ($dateFields as $field) {
            if (isset($validated[$field]) && $validated[$field]) {
                try {
                    $validated[$field] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated[$field])->format('Y-m-d');
                } catch (\Exception $e) {
                    // If date conversion fails, return validation error
                    return back()->withErrors([$field => 'تنسيق التاريخ غير صحيح. يجب أن يكون dd/mm/yyyy'])->withInput();
                }
            }
        }

        $client = Client::create($validated);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $client->addMedia($document)
                    ->toMediaCollection('documents');
            }
        }

        return redirect()->route('clients.show', $client)
            ->with('success', 'تم إنشاء العميل بنجاح');
    }

    public function show(Client $client)
    {
        $client->load(['appointments', 'orders', 'conversations', 'media', 'governorate', 'area', 'service', 'source']);
        $accessoryProducts = collect();
        if (is_array($client->accessories) && !empty($client->accessories)) {
            $accessoryProducts = \App\Models\Product::whereIn('id', $client->accessories)->get();
        }
        
        $recentAppointments = $client->appointments()
            ->orderBy('appointment_date', 'desc')
            ->take(5)
            ->get();
            
        $recentOrders = $client->orders()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('clients.show', compact('client', 'recentAppointments', 'recentOrders', 'accessoryProducts'));
    }

    public function print(Client $client)
    {
        $client->load(['appointments', 'orders', 'conversations', 'media', 'governorate', 'area', 'service', 'source']);
        $accessoryProducts = collect();
        if (is_array($client->accessories) && !empty($client->accessories)) {
            $accessoryProducts = \App\Models\Product::whereIn('id', $client->accessories)->get();
        }
        return view('clients.print', compact('client', 'accessoryProducts'));
    }

    public function edit(Client $client)
    {
        $services = Service::where('is_active', true)->get();
        $sources = \App\Models\ClientSource::where('is_active', true)->get();
        $products = \App\Models\Product::where('is_active', true)->where('status', 'active')->get();
        return view('clients.edit', compact('client', 'services', 'sources', 'products'));
    }

    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'groom_name' => 'nullable|string|max:255',
            'bride_name' => 'nullable|string|max:255',
            'guardian_name' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:500',
            'geographical_area' => 'nullable|string|max:255',
            'client_status' => 'required|in:new,in_progress,completed,cancelled',
            'call_result' => 'nullable|in:interested,not_interested,follow_up_later,potential_client,confirmed_booking,completed_booking,cancelled,inquiry,client_booking,no_answer,busy_number',
            'next_follow_up_date' => 'nullable|string',
            'relationship_status' => 'nullable|string|max:255',
            'bride_age' => 'nullable|integer|min:1|max:100',
            'accessories' => 'nullable|array',
            'accessories.*' => 'exists:products,id',
            'event_date' => 'nullable|string',
            'contract_location' => 'nullable|string|max:255',
            'contract_cost' => 'nullable|numeric|min:0',
            'discount_type' => 'nullable|in:percentage,fixed_amount',
            'discount_value' => 'nullable|numeric|min:0',
            'final_price' => 'nullable|numeric|min:0',
            'contract_address' => 'nullable|string|max:1000',
            'mahr' => 'nullable|string|max:255',
            'bride_id_address' => 'nullable|string|max:1000',
            'contract_delivery_method' => 'nullable|in:delivery,office',
            'contract_delivery_date' => 'nullable|string',
            'temporary_document' => 'nullable|string|max:255',
            'sheikh_name' => 'nullable|string|max:255',
            'book_number' => 'nullable|string|max:255',
            'document_number' => 'nullable|string|max:255',
            'coupon_arrival_date' => 'nullable|string',
            'document_receipt_date' => 'nullable|string',
            'document_receiver' => 'nullable|in:delivery,client,client_relative',
            'delivery_man_name' => 'nullable|string|max:255',
            'client_relative_name' => 'nullable|string|max:255',
            'google_maps_link' => 'nullable|url|max:500',
            'notes' => 'nullable|string|max:1000',
            'whatsapp_number' => 'nullable|string|max:20',
            'is_active' => 'boolean',
            'service_id' => 'nullable|exists:services,id',
            'source_id' => 'nullable|exists:client_sources,id',
            'area_id' => 'nullable|exists:areas,id',
            'governorate_id' => 'nullable|exists:governorates,id',
            'client_status' => 'nullable|in:new,in_progress,completed,cancelled',
        ]);

        // Convert date fields from dd/mm/yyyy to yyyy-mm-dd
        $dateFields = ['event_date', 'next_follow_up_date', 'coupon_arrival_date', 'document_receipt_date'];
        foreach ($dateFields as $field) {
            if (isset($validated[$field]) && $validated[$field]) {
                try {
                    $validated[$field] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated[$field])->format('Y-m-d');
                } catch (\Exception $e) {
                    return back()->withErrors([$field => 'تنسيق التاريخ غير صحيح. يجب أن يكون dd/mm/yyyy'])->withInput();
                }
            }
        }

        $client->update($validated);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $client->addMedia($document)
                    ->toMediaCollection('documents');
            }
        }

        return redirect()->route('clients.show', $client)
            ->with('success', 'تم تحديث بيانات العميل بنجاح');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')
            ->with('success', 'تم حذف العميل بنجاح');
    }

    public function conversations(Client $client)
    {
        $conversations = $client->conversations()
            ->orderBy('conversation_date', 'desc')
            ->paginate(20);

        return view('clients.conversations', compact('client', 'conversations'));
    }

    public function orders(Client $client)
    {
        $orders = $client->orders()
            ->with(['service', 'appointment'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('clients.orders', compact('client', 'orders'));
    }

    public function sendWhatsAppReminder(Client $client)
    {
        // TODO: Implement WhatsApp reminder logic
        return back()->with('success', 'تم إرسال تذكير واتساب للعميل');
    }

    public function addNote(Request $request, Client $client)
    {
        $validated = $request->validate([
            'note' => 'required|string|max:1000',
        ]);

        $client->notes = $client->notes . "\n" . now()->format('Y-m-d H:i:s') . ": " . $validated['note'];
        $client->save();

        return back()->with('success', 'تم إضافة الملاحظة بنجاح');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $clients = Client::where('name', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->with(['appointments', 'orders'])
            ->paginate(15);

        return view('clients.index', compact('clients', 'query'));
    }

    public function kanban()
    {
        $clients = Client::with(['appointments', 'orders', 'service'])
            ->active()
            ->get()
            ->groupBy('client_status');

        $stages = [
            'new' => 'جديد',
            'in_progress' => 'قيد التقدم',
            'completed' => 'مكتمل',
            'cancelled' => 'ملغي'
        ];

        return view('clients.kanban', compact('clients', 'stages'));
    }

    public function storeDocuments(Request $request, Client $client)
    {
        $request->validate([
            'documents.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $document) {
                $client->addMedia($document)
                    ->toMediaCollection('documents');
            }
        }

        return back()->with('success', 'تم رفع المستندات بنجاح');
    }

    public function destroyDocument(Client $client, $documentId)
    {
        $media = $client->media()->findOrFail($documentId);
        $media->delete();

        return back()->with('success', 'تم حذف المستند بنجاح');
    }

    public function createAppointment(Client $client)
    {
        $services = Service::all();
        $staff = User::active()->available()->get();
        return view('clients.create-appointment', compact('client', 'services', 'staff'));
    }

    public function storeAppointment(Request $request, Client $client)
    {
        $validated = $request->validate([
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:15',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Combine date and time
        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];
        $startDateTime = \Carbon\Carbon::parse($appointmentDateTime);
        
        // Calculate end time based on duration (ensure it's an integer)
        $duration = (int) $validated['duration'];
        $endDateTime = $startDateTime->copy()->addMinutes($duration);
        
        // Check for conflicts
        $conflicts = Appointment::where('status', '!=', 'cancelled')
            ->where(function($query) use ($startDateTime, $endDateTime) {
                $query->whereBetween('appointment_date', [$startDateTime, $endDateTime])
                    ->orWhereBetween('end_time', [$startDateTime, $endDateTime])
                    ->orWhere(function($q) use ($startDateTime, $endDateTime) {
                        $q->where('appointment_date', '<=', $startDateTime)
                          ->where('end_time', '>=', $endDateTime);
                    });
            })
            ->exists();

        if ($conflicts) {
            return back()->withErrors(['appointment_date' => 'هذا الوقت محجوز بالفعل. يرجى اختيار وقت آخر.'])->withInput();
        }

        $appointmentData = [
            'client_id' => $client->id,
            'service_id' => $validated['service_id'],
            'appointment_date' => $startDateTime,
            'end_time' => $endDateTime,
            'duration' => $duration,
            'location' => $validated['location'],
            'notes' => $validated['notes'],
            'assigned_to' => auth()->id(),
            'status' => 'scheduled',
            'execution_status' => 'scheduled'
        ];

        Appointment::create($appointmentData);

        // Update client's service
        $client->update(['service_id' => $validated['service_id']]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'تم إنشاء الموعد وتحديث خدمة العميل بنجاح');
    }

    public function transferToOperations(Client $client)
    {
        // Create a new task for operations
        $task = \App\Models\Task::create([
            'title' => 'مهمة جديدة - ' . $client->name,
            'description' => $this->generateTaskDescription($client),
            'priority' => 'medium',
            'status' => 'pending',
            'tag' => 'new',
            'due_date' => now()->addDays(3), // Due in 3 days
            'location' => $client->address,
            'execution_phase' => 'preparation',
            'is_active' => true,
            'assigned_to' => auth()->id(),
        ]);

        // Update client status to indicate it's been transferred to operations
        $client->update([
            'status' => 'in_progress',
            'document_status' => 'under_review'
        ]);

        return redirect()->route('clients.show', $client)
            ->with('success', 'تم تحويل العميل إلى التشغيل بنجاح. تم إنشاء مهمة جديدة برقم: ' . $task->id);
    }

    private function generateTaskDescription(Client $client): string
    {
        $description = "تفاصيل العميل:\n";
        $description .= "الاسم: " . $client->name . "\n";
        
        if ($client->bride_name) {
            $description .= "اسم العروس: " . $client->bride_name . "\n";
        }
        
        if ($client->guardian_name) {
            $description .= "ولي الأمر: " . $client->guardian_name . "\n";
        }
        
        $description .= "رقم الهاتف: " . $client->phone . "\n";
        
        if ($client->email) {
            $description .= "البريد الإلكتروني: " . $client->email . "\n";
        }
        
        if ($client->address) {
            $description .= "العنوان: " . $client->address . "\n";
        }
        
        if ($client->geographical_area) {
            $description .= "المنطقة الجغرافية: " . $client->geographical_area . "\n";
        }
        
        if ($client->governorate) {
            $description .= "المحافظة: " . $client->governorate . "\n";
        }
        
        if ($client->area) {
            $description .= "المنطقة: " . $client->area . "\n";
        }
        
        if ($client->google_maps_link) {
            $description .= "رابط الموقع: " . $client->google_maps_link . "\n";
        }
        
        if ($client->relationship_status) {
            $description .= "صلة القرابة: " . $client->relationship_status . "\n";
        }
        
        if ($client->call_result) {
            $description .= "نتيجة المكالمة: " . $client->getCallResultLabelAttribute() . "\n";
        }
        
        if ($client->next_follow_up_date) {
            $description .= "تاريخ المتابعة: " . $client->next_follow_up_date->format('Y-m-d') . "\n";
        }
        
        if ($client->service) {
            $description .= "الخدمة: " . $client->service->name . "\n";
        }
        
        if ($client->notes) {
            $description .= "الملاحظات: " . $client->notes . "\n";
        }

        return $description;
    }
}

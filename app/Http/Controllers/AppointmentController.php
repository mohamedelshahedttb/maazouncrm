<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with(['client', 'service'])->paginate(15);
        return view('appointments.index', compact('appointments'));
    }



    public function create()
    {
        $clients = Client::all();
        $services = Service::all();
        $staff = User::active()->available()->get();
        return view('appointments.create', compact('clients', 'services', 'staff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:15',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Combine date and time
        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];
        $startDateTime = \Carbon\Carbon::parse($appointmentDateTime);
        
        // Calculate end time based on duration
        $endDateTime = $startDateTime->copy()->addMinutes($validated['duration']);
        
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
            'client_id' => $validated['client_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $startDateTime,
            'end_time' => $endDateTime,
            'location' => $validated['location'],
            'notes' => $validated['notes'],
            'assigned_to' => $validated['assigned_to'] ?? auth()->id(),
            'status' => 'scheduled',
            'execution_status' => 'scheduled'
        ];

        Appointment::create($appointmentData);

        return redirect()->route('appointments.index')
            ->with('success', 'تم إنشاء الموعد بنجاح');
    }

    public function show(Appointment $appointment)
    {
        return view('appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        $clients = Client::all();
        $services = Service::all();
        $staff = User::active()->available()->get();
        return view('appointments.edit', compact('appointment', 'clients', 'services', 'staff'));
    }

    public function update(Request $request, Appointment $appointment)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'appointment_date' => 'required|date',
            'appointment_time' => 'required',
            'duration' => 'required|integer|min:15',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'assigned_to' => 'required|exists:users,id',
        ]);

        // Combine date and time
        $appointmentDateTime = $validated['appointment_date'] . ' ' . $validated['appointment_time'];
        $startDateTime = \Carbon\Carbon::parse($appointmentDateTime);
        
        // Calculate end time based on duration
        $endDateTime = $startDateTime->copy()->addMinutes($validated['duration']);
        
        // Check for conflicts (excluding current appointment)
        $conflicts = Appointment::where('id', '!=', $appointment->id)
            ->where('status', '!=', 'cancelled')
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
            'client_id' => $validated['client_id'],
            'service_id' => $validated['service_id'],
            'appointment_date' => $startDateTime,
            'end_time' => $endDateTime,
            'location' => $validated['location'],
            'notes' => $validated['notes'],
            'assigned_to' => $validated['assigned_to'],
        ];

        $appointment->update($appointmentData);

        return redirect()->route('appointments.index')
            ->with('success', 'تم تحديث الموعد بنجاح');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();
        return redirect()->route('appointments.index')
            ->with('success', 'تم حذف الموعد بنجاح');
    }

    public function calendar()
    {
        $appointments = Appointment::with(['client', 'service'])
            ->where('status', '!=', 'cancelled')
            ->get();
        
        return view('appointments.calendar', compact('appointments'));
    }

    public function confirm(Appointment $appointment)
    {
        $appointment->update(['status' => 'confirmed']);
        return back()->with('success', 'تم تأكيد الموعد');
    }

    public function cancel(Appointment $appointment)
    {
        $appointment->update(['status' => 'cancelled']);
        return back()->with('success', 'تم إلغاء الموعد');
    }
}

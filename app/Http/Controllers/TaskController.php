<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Appointment;
use App\Models\Partner;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::with(['appointment', 'partner'])->paginate(15);
        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        $appointments = Appointment::all();
        $partners = Partner::all();
        return view('tasks.create', compact('appointments', 'partners'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'appointment_id' => 'nullable|exists:appointments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'partner_id' => 'nullable|exists:partners,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled,delayed',
            'book_number' => 'nullable|string|max:255',
            'due_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['assigned_to'] = $validated['assigned_to'] ?? auth()->id();

        Task::create($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'تم إنشاء المهمة بنجاح');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        $appointments = Appointment::all();
        $partners = Partner::all();
        return view('tasks.edit', compact('task', 'appointments', 'partners'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'appointment_id' => 'nullable|exists:appointments,id',
            'assigned_to' => 'nullable|exists:users,id',
            'partner_id' => 'nullable|exists:partners,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in_progress,completed,cancelled,delayed',
            'book_number' => 'nullable|string|max:255',
            'due_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $task->update($validated);

        return redirect()->route('tasks.index')
            ->with('success', 'تم تحديث المهمة بنجاح');
    }

    public function destroy(Task $task)
    {
        $task->delete();
        return redirect()->route('tasks.index')
            ->with('success', 'تم حذف المهمة بنجاح');
    }

    public function calendar()
    {
        return view('tasks.calendar');
    }

    public function start(Task $task)
    {
        $task->update([
            'status' => 'in_progress',
            'started_at' => now()
        ]);
        return back()->with('success', 'تم بدء المهمة');
    }

    public function complete(Task $task)
    {
        $task->update([
            'status' => 'completed',
            'completed_at' => now()
        ]);
        return back()->with('success', 'تم إكمال المهمة');
    }
}

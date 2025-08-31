<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Client;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index()
    {
        $conversations = Conversation::with(['client', 'user'])->paginate(15);
        return view('conversations.index', compact('conversations'));
    }

    public function create()
    {
        $clients = Client::all();
        return view('conversations.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:phone,whatsapp,email,in_person,other',
            'content' => 'required|string|max:2000',
            'summary' => 'nullable|string|max:500',
            'direction' => 'required|in:incoming,outgoing',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'status' => 'required|in:active,resolved,pending_followup',
            'conversation_date' => 'required|date',
            'follow_up_notes' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $validated['user_id'] = auth()->id();

        Conversation::create($validated);

        return redirect()->route('conversations.index')
            ->with('success', 'تم إضافة المحادثة بنجاح');
    }

    public function show(Conversation $conversation)
    {
        return view('conversations.show', compact('conversation'));
    }

    public function edit(Conversation $conversation)
    {
        $clients = Client::all();
        return view('conversations.edit', compact('conversation', 'clients'));
    }

    public function update(Request $request, Conversation $conversation)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'type' => 'required|in:phone,whatsapp,email,in_person,other',
            'content' => 'required|string|max:2000',
            'summary' => 'nullable|string|max:500',
            'direction' => 'required|in:incoming,outgoing',
            'phone_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'nullable|email|max:255',
            'status' => 'required|in:active,resolved,pending_followup',
            'conversation_date' => 'required|date',
            'follow_up_notes' => 'nullable|string|max:1000',
            'follow_up_date' => 'nullable|date',
            'is_active' => 'boolean',
        ]);

        $conversation->update($validated);

        return redirect()->route('conversations.index')
            ->with('success', 'تم تحديث المحادثة بنجاح');
    }

    public function destroy(Conversation $conversation)
    {
        $conversation->delete();
        return redirect()->route('conversations.index')
            ->with('success', 'تم حذف المحادثة بنجاح');
    }
}

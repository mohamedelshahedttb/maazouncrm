@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">إدارة المحادثات</h1>
            <p class="text-gray-600 mt-2">إدارة جميع المحادثات مع العملاء</p>
        </div>
        <a href="{{ route('conversations.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            إضافة محادثة جديدة +
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">قائمة المحادثات</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">نوع المحادثة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($conversations as $conversation)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $conversation->client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $conversation->client->phone }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $conversation->type === 'whatsapp' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $conversation->type === 'phone' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $conversation->type === 'email' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $conversation->type === 'in_person' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ $conversation->type }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $conversation->conversation_date->format('Y-m-d H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                {{ $conversation->status === 'active' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $conversation->status === 'resolved' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $conversation->status === 'pending_followup' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ $conversation->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2 space-x-reverse">
                                <a href="{{ route('conversations.show', $conversation) }}" class="text-blue-600 hover:text-blue-900">
                                    عرض
                                </a>
                                <a href="{{ route('conversations.edit', $conversation) }}" class="text-green-600 hover:text-green-900">
                                    تعديل
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">لا توجد محادثات</h3>
                                <p class="mt-1 text-sm text-gray-500">ابدأ بإضافة محادثة جديدة</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($conversations->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $conversations->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

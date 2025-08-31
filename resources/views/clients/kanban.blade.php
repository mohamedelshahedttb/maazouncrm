@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title and Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">لوحة العملاء</h1>
            <p class="text-gray-600 mt-2">عرض العملاء حسب المراحل - عرض كانبان</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-blue-100 px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                عرض جدولي
            </a>
            <a href="{{ route('clients.create') }}" class="bg-blue-500 hover:bg-blue-600 text-blue-100 px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                إضافة عميل جديد
            </a>
        </div>
    </div>

    <!-- Kanban Board -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        @foreach($stages as $status => $stageName)
        <div class="bg-gray-50 rounded-lg p-4">
            <!-- Stage Header -->
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">{{ $stageName }}</h3>
                <span class="bg-white text-gray-600 px-2 py-1 rounded-full text-sm font-medium">
                    {{ $clients->get($status, collect())->count() }}
                </span>
            </div>

            <!-- Stage Color Indicator -->
            <div class="w-full h-2 rounded-full mb-4 
                @if($status === 'new') bg-blue-500
                @elseif($status === 'in_progress') bg-yellow-500
                @elseif($status === 'completed') bg-green-500
                @else bg-red-500
                @endif">
            </div>

            <!-- Client Cards -->
            <div class="space-y-3">
                @forelse($clients->get($status, collect()) as $client)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 hover:shadow-md transition-shadow cursor-pointer" 
                     onclick="window.location.href='{{ route('clients.show', $client) }}'">
                    
                    <!-- Client Header -->
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center ml-2">
                                <span class="text-blue-600 font-semibold text-sm">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 text-sm">{{ $client->name }}</h4>
                                @if($client->bride_name)
                                <p class="text-xs text-gray-500">{{ $client->bride_name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-1 space-x-reverse">
                            <a href="{{ route('clients.edit', $client) }}" class="text-gray-400 hover:text-blue-600 p-1" title="تعديل">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Client Details -->
                    <div class="space-y-2 mb-3">
                        <div class="flex items-center text-xs text-gray-600">
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $client->phone }}
                        </div>
                        
                        @if($client->email)
                        <div class="flex items-center text-xs text-gray-600">
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            {{ $client->email }}
                        </div>
                        @endif

                        @if($client->service)
                        <div class="flex items-center text-xs text-gray-600">
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $client->service->name }}
                        </div>
                        @endif
                    </div>

                    <!-- Client Stats -->
                    <div class="flex items-center justify-between text-xs text-gray-500">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            {{ $client->appointments->count() }} موعد
                        </div>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $client->orders->count() }} طلب
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex space-x-2 space-x-reverse mt-3 pt-3 border-t border-gray-100">
                        <a href="{{ route('clients.show', $client) }}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">
                            عرض التفاصيل
                        </a>
                        <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="text-xs text-green-600 hover:text-green-800 font-medium">
                            إنشاء فاتورة
                        </a>
                    </div>
                </div>
                @empty
                <div class="bg-white rounded-lg border-2 border-dashed border-gray-300 p-6 text-center">
                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <p class="text-sm text-gray-500">لا يوجد عملاء</p>
                </div>
                @endforelse
            </div>

            <!-- Add Client Button for this stage -->
            <div class="mt-4">
                <a href="{{ route('clients.create', ['status' => $status]) }}" 
                   class="w-full bg-white border-2 border-dashed border-gray-300 rounded-lg p-3 text-center text-gray-500 hover:border-blue-300 hover:text-blue-600 transition-colors">
                    <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="text-sm">إضافة عميل</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Summary Stats -->
    <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">إجمالي العملاء</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $clients->flatten()->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">العملاء المكتملين</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $clients->get('completed', collect())->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">قيد التقدم</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $clients->get('in_progress', collect())->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center ml-3">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-600">العملاء الجدد</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $clients->get('new', collect())->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Custom scrollbar for kanban columns */
.space-y-3 {
    max-height: 70vh;
    overflow-y: auto;
}

.space-y-3::-webkit-scrollbar {
    width: 6px;
}

.space-y-3::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.space-y-3::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.space-y-3::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}
</style>
@endsection

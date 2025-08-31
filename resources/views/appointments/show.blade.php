@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">تفاصيل الموعد</h1>
                <p class="text-gray-600 mt-2">معلومات الموعد: {{ $appointment->client->name }}</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('appointments.edit', $appointment) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تعديل
                </a>
                <a href="{{ route('appointments.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">العميل</label>
                    <p class="text-gray-900">{{ $appointment->client->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الخدمة</label>
                    <p class="text-gray-900">{{ $appointment->service->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">التاريخ</label>
                    <p class="text-gray-900">{{ $appointment->appointment_date->format('Y-m-d') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الوقت</label>
                    <p class="text-gray-900">{{ $appointment->appointment_date->format('H:i') }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">المدة</label>
                    <p class="text-gray-900">{{ $appointment->end_time ? $appointment->appointment_date->diffInMinutes($appointment->end_time) : 'غير محدد' }} دقيقة</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                        {{ $appointment->status === 'confirmed' ? 'bg-green-100 text-green-800' : '' }}
                        {{ $appointment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                        {{ $appointment->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ $appointment->status }}
                    </span>
                </div>
                
                @if($appointment->location)
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الموقع</label>
                    <p class="text-gray-900">{{ $appointment->location }}</p>
                </div>
                @endif
                
                @if($appointment->notes)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-2">الملاحظات</label>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $appointment->notes }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">تعديل الموعد</h1>
        <p class="text-gray-600 mt-2">تعديل تفاصيل الموعد: {{ $appointment->client->name }}</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('appointments.update', $appointment) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">العميل *</label>
                    <select name="client_id" id="client_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر العميل</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $appointment->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} - {{ $client->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">الخدمة *</label>
                    <select name="service_id" id="service_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الخدمة</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $appointment->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} - {{ $service->price }} {{ $service->currency }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="appointment_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الموعد *</label>
                    <input type="date" name="appointment_date" id="appointment_date" required 
                           value="{{ old('appointment_date', $appointment->appointment_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="appointment_time" class="block text-sm font-medium text-gray-700 mb-2">وقت الموعد *</label>
                    <input type="time" name="appointment_time" id="appointment_time" required 
                           value="{{ old('appointment_time', $appointment->appointment_date->format('H:i')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">مدة الموعد (دقائق) *</label>
                    <input type="number" name="duration" id="duration" required min="15" step="15"
                           value="{{ old('duration', $appointment->end_time ? $appointment->appointment_date->diffInMinutes($appointment->end_time) : 60) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">تعيين للموظف/الشريك *</label>
                    <select name="assigned_to" id="assigned_to" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الموظف أو الشريك</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('assigned_to', $appointment->assigned_to) == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->role_label }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('appointments.show', $appointment) }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تحديث الموعد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

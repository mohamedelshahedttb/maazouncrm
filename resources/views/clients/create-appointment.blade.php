@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-800">إنشاء موعد للعميل</h1>
        <p class="text-blue-600 mt-2">إنشاء موعد جديد للعميل: {{ $client->name }}</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('clients.appointments.store', $client) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client Info (Read-only) -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">معلومات العميل</label>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <span class="text-sm font-medium text-gray-500">الاسم:</span>
                                <span class="text-sm text-gray-900 mr-2">{{ $client->name }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">الهاتف:</span>
                                <span class="text-sm text-gray-900 mr-2">{{ $client->phone }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">البريد الإلكتروني:</span>
                                <span class="text-sm text-gray-900 mr-2">{{ $client->email ?? 'غير محدد' }}</span>
                            </div>
                            <div>
                                <span class="text-sm font-medium text-gray-500">الحالة:</span>
                                <span class="text-sm text-gray-900 mr-2">{{ $client->status_label }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Selection -->
                <div class="md:col-span-2">
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">اختر الخدمة *</label>
                    <select name="service_id" id="service_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الخدمة</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $client->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} - {{ $service->price }} جنيه مصري
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Staff/Partner Assignment -->
                <div class="md:col-span-2">
                    <label for="assigned_to" class="block text-sm font-medium text-blue-700 mb-2">تعيين للموظف/الالشيخ *</label>
                    <select name="assigned_to" id="assigned_to" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الموظف أو الالشيخ</option>
                        @foreach($staff as $member)
                            <option value="{{ $member->id }}" {{ old('assigned_to') == $member->id ? 'selected' : '' }}>
                                {{ $member->name }} ({{ $member->role_label }})
                            </option>
                        @endforeach
                    </select>
                    @error('assigned_to')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Date and Time Selection -->
                <div>
                    <label for="appointment_date" class="block text-sm font-medium text-blue-700 mb-2">تاريخ الموعد *</label>
                    <input type="date" name="appointment_date" id="appointment_date" required 
                           min="{{ date('Y-m-d') }}" 
                           value="{{ old('appointment_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('appointment_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="appointment_time" class="block text-sm font-medium text-blue-700 mb-2">وقت الموعد *</label>
                    <input type="time" name="appointment_time" id="appointment_time" required 
                           value="{{ old('appointment_time') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('appointment_time')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration" class="block text-sm font-medium text-gray-700 mb-2">مدة الخدمة (بالدقائق) *</label>
                    <input type="number" name="duration" id="duration" required 
                           min="15" step="15" value="{{ old('duration', 60) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('duration')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-2">مكان الموعد</label>
                    <input type="text" name="location" id="location" 
                           value="{{ old('location') }}"
                           placeholder="مكتبنا الرئيسي"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="أي ملاحظات إضافية حول الموعد..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('clients.show', $client) }}" class="px-6 py-3 border border-gray-300 text-blue-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-blue rounded-lg font-medium">
                    إنشاء الموعد
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dateInput = document.getElementById('appointment_date');
    const timeInput = document.getElementById('appointment_time');
    const durationInput = document.getElementById('duration');
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    dateInput.min = today;
    
    // Set default time to next available slot
    const now = new Date();
    const nextHour = new Date(now.getTime() + 60 * 60 * 1000);
    timeInput.value = nextHour.toTimeString().slice(0, 5);
    
    // Calculate end time when date, time, or duration changes
    function updateEndTime() {
        if (dateInput.value && timeInput.value && durationInput.value) {
            const startDateTime = new Date(dateInput.value + 'T' + timeInput.value);
            const endDateTime = new Date(startDateTime.getTime() + parseInt(durationInput.value) * 60 * 1000);
            
            // Update hidden end_time field
            const endTimeInput = document.createElement('input');
            endTimeInput.type = 'hidden';
            endTimeInput.name = 'end_time';
            endTimeInput.value = endDateTime.toISOString();
            
            // Remove existing end_time field if exists
            const existingEndTime = document.querySelector('input[name="end_time"]');
            if (existingEndTime) {
                existingEndTime.remove();
            }
            
            document.querySelector('form').appendChild(endTimeInput);
        }
    }
    
    dateInput.addEventListener('change', updateEndTime);
    timeInput.addEventListener('change', updateEndTime);
    durationInput.addEventListener('change', updateEndTime);
    
    // Initial calculation
    updateEndTime();
});
</script>
@endsection

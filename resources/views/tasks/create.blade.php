@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-blue-800">إنشاء مهمة جديدة</h1>
        <p class="text-gray-600 mt-2">إضافة مهمة جديدة مع تحديد الأولوية والتواريخ</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('tasks.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Task Title -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-blue-700 mb-2">عنوان المهمة *</label>
                    <input type="text" name="title" id="title" required 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('title')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-blue-700 mb-2">وصف المهمة</label>
                    <textarea name="description" id="description" rows="3" 
                              placeholder="وصف مفصل للمهمة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Appointment (Optional) -->
                <div>
                    <label for="appointment_id" class="block text-sm font-medium text-blue-700 mb-2">الموعد (اختياري)</label>
                    <select name="appointment_id" id="appointment_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">بدون موعد</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->client->name }} - {{ $appointment->service->name }} - {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Partner (Optional) -->
                <div>
                    <label for="partner_id" class="block text-sm font-medium text-blue-700 mb-2">الالشيخ (اختياري)</label>
                    <select name="partner_id" id="partner_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">بدون الشيخ</option>
                        @foreach($partners as $partner)
                            <option value="{{ $partner->id }}" {{ old('partner_id') == $partner->id ? 'selected' : '' }}>
                                {{ $partner->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('partner_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-medium text-blue-700 mb-2">الأولوية *</label>
                    <select name="priority" id="priority" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الأولوية</option>
                        <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>منخفضة</option>
                        <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>متوسطة</option>
                        <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>عالية</option>
                        <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>عاجلة</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-blue-700 mb-2">الحالة *</label>
                    <select name="status" id="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الحالة</option>
                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>معلق</option>
                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>قيد التنفيذ</option>
                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        <option value="delayed" {{ old('status') == 'delayed' ? 'selected' : '' }}>متأخر</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-blue-700 mb-2">تاريخ الاستحقاق *</label>
                    <input type="datetime-local" name="due_date" id="due_date" required 
                           value="{{ old('due_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Location -->
                <div>
                    <label for="location" class="block text-sm font-medium text-blue-700 mb-2">الموقع</label>
                    <input type="text" name="location" id="location" 
                           value="{{ old('location') }}"
                           placeholder="موقع تنفيذ المهمة"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('location')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm font-medium text-blue-700">نشط</span>
                    </label>
                </div>

                <!-- Book Number -->
                <div>
                    <label for="book_number" class="block text-sm font-medium text-blue-700 mb-2">رقم الدفتر</label>
                    <input type="text" name="book_number" id="book_number" 
                           value="{{ old('book_number') }}"
                           placeholder="رقم الدفتر"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('book_number')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-blue-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="أي ملاحظات إضافية حول المهمة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('tasks.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                                            <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-blue-100 rounded-lg font-medium hover:text-white">
                                إنشاء المهمة
                            </button>
            </div>
        </form>
    </div>
</div>
@endsection

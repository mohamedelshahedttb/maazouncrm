@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إضافة عميل جديد</h1>
        <p class="text-gray-600 mt-2">املأ التفاصيل لتسجيل عميل جديد في النظام</p>
    </div>

    <!-- Client Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">معلومات العميل</h3>
        </div>
        
        <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Client Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم العميل *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم العميل">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bride Name -->
                    <div>
                        <label for="bride_name" class="block text-sm font-medium text-gray-700 mb-2">اسم العروس</label>
                        <input type="text" name="bride_name" id="bride_name" value="{{ old('bride_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم العروس">
                        @error('bride_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Guardian Name -->
                    <div>
                        <label for="guardian_name" class="block text-sm font-medium text-gray-700 mb-2">اسم ولي العروس</label>
                        <input type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم ولي العروس">
                        @error('guardian_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف *</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 05XXXXXXXXX">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: client@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- WhatsApp Number -->
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الواتساب</label>
                        <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 05XXXXXXXXX">
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="أدخل العنوان الكامل">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة العميل</label>
                        <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="new" {{ old('status') == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>قيد التقدم</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>ملغى</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Selection -->
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">الخدمة المطلوبة</label>
                        <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر الخدمة</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - {{ $service->price }} {{ $service->currency }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client Source -->
                    <div>
                        <label for="source_id" class="block text-sm font-medium text-gray-700 mb-2">مصدر العميل</label>
                        <select name="source_id" id="source_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر المصدر</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}" {{ old('source_id') == $source->id ? 'selected' : '' }}>
                                    {{ $source->name }} ({{ $source->type_label }})
                                </option>
                            @endforeach
                        </select>
                        @error('source_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea name="notes" id="notes" rows="4"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="أدخل أي ملاحظات أو تفاصيل إضافية">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Documents -->
            <div class="mt-6">
                <label for="documents" class="block text-sm font-medium text-gray-700 mb-2">المستندات</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-gray-600 mb-2">اسحب وأفلت الملفات هنا، أو</p>
                    <input type="file" name="documents[]" id="documents" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="hidden">
                    <label for="documents" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        اختيار الملفات
                    </label>
                    <p class="text-sm text-gray-500 mt-2">PDF, DOC, DOCX, JPG, PNG (الحد الأقصى: 10 ملفات)</p>
                </div>
                @error('documents')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('clients.index') }}" class="px-6 py-2 border border-gray-300 text-red-700 rounded-lg hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-blue-100 rounded-lg hover:bg-blue-600 hover:text-blue font-medium">
                    حفظ العميل +
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// File upload preview
document.getElementById('documents').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const container = e.target.parentElement;
    
    // Remove existing preview
    const existingPreview = container.querySelector('.file-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    if (files.length > 0) {
        const preview = document.createElement('div');
        preview.className = 'file-preview mt-4';
        preview.innerHTML = '<p class="text-sm font-medium text-gray-700 mb-2">الملفات المختارة:</p>';
        
        files.forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded mb-2';
            fileItem.innerHTML = `
                <span class="text-sm text-gray-600">${file.name}</span>
                <span class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            `;
            preview.appendChild(fileItem);
        });
        
        container.appendChild(preview);
    }
});
</script>
@endsection

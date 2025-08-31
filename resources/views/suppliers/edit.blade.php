@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">تعديل المورد</h1>
        <p class="text-gray-600 mt-2">تعديل بيانات المورد: {{ $supplier->name }}</p>
    </div>

    <!-- Supplier Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">معلومات المورد</h3>
        </div>
        
        <form method="POST" action="{{ route('suppliers.update', $supplier) }}" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Supplier Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المورد *</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $supplier->name) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم المورد">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Person -->
                    <div>
                        <label for="contact_person" class="block text-sm font-medium text-gray-700 mb-2">جهة الاتصال</label>
                        <input type="text" name="contact_person" id="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم جهة الاتصال">
                        @error('contact_person')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف *</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone', $supplier->phone) }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 05XXXXXXXXX">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $supplier->email) }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: supplier@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Service Type -->
                    <div>
                        <label for="service_type" class="block text-sm font-medium text-gray-700 mb-2">نوع الخدمات *</label>
                        <select name="service_type" id="service_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر نوع الخدمة</option>
                            <option value="دفاتر عقود" {{ old('service_type', $supplier->service_type) == 'دفاتر عقود' ? 'selected' : '' }}>دفاتر عقود</option>
                            <option value="طابعات" {{ old('service_type', $supplier->service_type) == 'طابعات' ? 'selected' : '' }}>طابعات</option>
                            <option value="مستندات رسمية" {{ old('service_type', $supplier->service_type) == 'مستندات رسمية' ? 'selected' : '' }}>مستندات رسمية</option>
                            <option value="أدوات مكتبية" {{ old('service_type', $supplier->service_type) == 'أدوات مكتبية' ? 'selected' : '' }}>أدوات مكتبية</option>
                            <option value="أخرى" {{ old('service_type', $supplier->service_type) == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('service_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Rating -->
                    <div>
                        <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">التقييم</label>
                        <select name="rating" id="rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="1" {{ old('rating', $supplier->rating) == '1' ? 'selected' : '' }}>1 - ضعيف</option>
                            <option value="2" {{ old('rating', $supplier->rating) == '2' ? 'selected' : '' }}>2 - مقبول</option>
                            <option value="3" {{ old('rating', $supplier->rating) == '3' ? 'selected' : '' }}>3 - جيد</option>
                            <option value="4" {{ old('rating', $supplier->rating) == '4' ? 'selected' : '' }}>4 - ممتاز</option>
                            <option value="5" {{ old('rating', $supplier->rating) == '5' ? 'selected' : '' }}>5 - رائع</option>
                        </select>
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                        <textarea name="address" id="address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="أدخل العنوان الكامل">{{ old('address', $supplier->address) }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', $supplier->is_active) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="mr-2 text-sm font-medium text-gray-700">نشط</span>
                        </label>
                        @error('is_active')
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
                          placeholder="أدخل أي ملاحظات أو تفاصيل إضافية">{{ old('notes', $supplier->notes) }}</textarea>
                @error('notes')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('suppliers.show', $supplier) }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-medium">
                    تحديث المورد
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

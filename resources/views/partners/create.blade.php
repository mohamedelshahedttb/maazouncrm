@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إضافة شريك جديد</h1>
        <p class="text-gray-600 mt-2">املأ التفاصيل لتسجيل شريك جديد في النظام</p>
    </div>

    <!-- Partner Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">معلومات الشريك</h3>
        </div>
        
        <form method="POST" action="{{ route('partners.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Partner Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الشريك *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم الشريك">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Office Name -->
                    <div>
                        <label for="office_name" class="block text-sm font-medium text-gray-700 mb-2">اسم المكتب</label>
                        <input type="text" name="office_name" id="office_name" value="{{ old('office_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم المكتب">
                        @error('office_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Office Address -->
                    <div>
                        <label for="office_address" class="block text-sm font-medium text-gray-700 mb-2">عنوان المكتب</label>
                        <textarea name="office_address" id="office_address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="أدخل عنوان المكتب">{{ old('office_address') }}</textarea>
                        @error('office_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agent Name -->
                    <div>
                        <label for="agent_name" class="block text-sm font-medium text-gray-700 mb-2">الوكيل من المأذون</label>
                        <input type="text" name="agent_name" id="agent_name" value="{{ old('agent_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم الوكيل">
                        @error('agent_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agent Phone -->
                    <div>
                        <label for="agent_phone" class="block text-sm font-medium text-gray-700 mb-2">رقم هاتف الوكيل</label>
                        <input type="tel" name="agent_phone" id="agent_phone" value="{{ old('agent_phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 05XXXXXXXXX">
                        @error('agent_phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Agent Email -->
                    <div>
                        <label for="agent_email" class="block text-sm font-medium text-gray-700 mb-2">بريد الوكيل الإلكتروني</label>
                        <input type="email" name="agent_email" id="agent_email" value="{{ old('agent_email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: agent@example.com">
                        @error('agent_email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- License Number -->
                    <div>
                        <label for="license_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الترخيص *</label>
                        <input type="text" name="license_number" id="license_number" value="{{ old('license_number') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل رقم الترخيص">
                        @error('license_number')
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

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: partner@example.com">
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- Service Scope -->
                    <div>
                        <label for="service_scope" class="block text-sm font-medium text-gray-700 mb-2">نطاق الخدمات *</label>
                        <select name="service_scope" id="service_scope" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر نطاق الخدمة</option>
                            <option value="الزواج" {{ old('service_scope') == 'الزواج' ? 'selected' : '' }}>الزواج</option>
                            <option value="الطلاق" {{ old('service_scope') == 'الطلاق' ? 'selected' : '' }}>الطلاق</option>
                            <option value="التصديق على المستندات" {{ old('service_scope') == 'التصديق على المستندات' ? 'selected' : '' }}>التصديق على المستندات</option>
                            <option value="الوصية" {{ old('service_scope') == 'الوصية' ? 'selected' : '' }}>الوصية</option>
                            <option value="أخرى" {{ old('service_scope') == 'أخرى' ? 'selected' : '' }}>أخرى</option>
                        </select>
                        @error('service_scope')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Commission Rate -->
                    <div>
                        <label for="commission_rate" class="block text-sm font-medium text-gray-700 mb-2">نسبة العمولة (%)</label>
                        <input type="number" name="commission_rate" id="commission_rate" value="{{ old('commission_rate', 10) }}" min="0" max="100" step="0.1"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 10">
                        @error('commission_rate')
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

                    <!-- Location Number -->
                    <div>
                        <label for="location_number" class="block text-sm font-medium text-gray-700 mb-2">موقع</label>
                        <input type="text" name="location_number" id="location_number" value="{{ old('location_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل رقم الموقع">
                        @error('location_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Book Number -->
                    <div>
                        <label for="book_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الدفتر</label>
                        <input type="text" name="book_number" id="book_number" value="{{ old('book_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل رقم الدفتر">
                        @error('book_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Number -->
                    <div>
                        <label for="document_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الوثيقه</label>
                        <input type="text" name="document_number" id="document_number" value="{{ old('document_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل رقم الوثيقه">
                        @error('document_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div>
                        <label class="flex items-center">
                            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                                   class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="mr-2 text-sm font-medium text-gray-700">نشط</span>
                        </label>
                        @error('is_active')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة *</label>
                        <select name="status" id="status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر الحالة</option>
                            <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>معلق</option>
                        </select>
                        @error('status')
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

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('partners.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 font-medium">
                    حفظ الشريك +
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إضافة مستخدم جديد</h1>
        <p class="text-gray-600 mt-2">إضافة مستخدم جديد مع تحديد الدور والصلاحيات</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('users.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل *</label>
                    <input type="text" name="name" id="name" required 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني *</label>
                    <input type="email" name="email" id="email" required 
                           value="{{ old('email') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">كلمة المرور *</label>
                    <input type="password" name="password" id="password" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">تأكيد كلمة المرور *</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="tel" name="phone" id="phone" 
                           value="{{ old('phone') }}"
                           placeholder="+966501234567"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('phone')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">الدور *</label>
                    <select name="role" id="role" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الدور</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>مدير</option>
                        <option value="staff" {{ old('role') == 'staff' ? 'selected' : '' }}>موظف</option>
                        <option value="partner" {{ old('role') == 'partner' ? 'selected' : '' }}>شريك</option>
                    </select>
                    @error('role')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Specialization -->
                <div>
                    <label for="specialization" class="block text-sm font-medium text-gray-700 mb-2">التخصص</label>
                    <input type="text" name="specialization" id="specialization" 
                           value="{{ old('specialization') }}"
                           placeholder="مثال: محاماة، ترجمة، توثيق"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('specialization')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Active Status -->
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm font-medium text-gray-700">نشط</span>
                    </label>
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="أي ملاحظات إضافية حول المستخدم..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('users.index') }}" class="px-6 py-3 border border-red-300 text-red-700 rounded-lg font-medium hover:bg-red-50">
                    إلغاء
                </a>
                                            <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-blue-100 rounded-lg font-medium hover:text-black">
                                إنشاء المستخدم
                            </button>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إضافة خدمة جديدة</h1>
        <p class="text-gray-600 mt-2">إضافة خدمة جديدة مع تحديد السعر والمتطلبات</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('services.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Service Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم الخدمة *</label>
                    <input type="text" name="name" id="name" required 
                           value="{{ old('name') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">الفئة *</label>
                    <select name="category" id="category" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الفئة</option>
                        <option value="marriage" {{ old('category') == 'marriage' ? 'selected' : '' }}>زواج</option>
                        <option value="divorce" {{ old('category') == 'divorce' ? 'selected' : '' }}>طلاق</option>
                        <option value="notarization" {{ old('category') == 'notarization' ? 'selected' : '' }}>توثيق</option>
                        <option value="translation" {{ old('category') == 'translation' ? 'selected' : '' }}>ترجمة</option>
                        <option value="consultation" {{ old('category') == 'consultation' ? 'selected' : '' }}>استشارة</option>
                        <option value="other" {{ old('category') == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">السعر *</label>
                    <input type="number" name="price" id="price" required 
                           min="0" step="0.01" value="{{ old('price') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">العملة *</label>
                    <select name="currency" id="currency" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر العملة</option>
                        <option value="EGP" {{ old('currency', 'EGP') == 'EGP' ? 'selected' : '' }}>جنيه مصري</option>
                        <option value="USD" {{ old('currency') == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                        <option value="EUR" {{ old('currency') == 'EUR' ? 'selected' : '' }}>يورو</option>
                        <option value="AED" {{ old('currency') == 'AED' ? 'selected' : '' }}>درهم إماراتي</option>
                    </select>
                    @error('currency')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration -->
                <div>
                    <label for="duration_minutes" class="block text-sm font-medium text-gray-700 mb-2">المدة (بالدقائق)</label>
                    <input type="number" name="duration_minutes" id="duration_minutes" 
                           min="0" value="{{ old('duration_minutes') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('duration_minutes')
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

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الخدمة</label>
                    <textarea name="description" id="description" rows="3" 
                              placeholder="وصف مفصل للخدمة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Requirements -->
                <div class="md:col-span-2">
                    <label for="requirements" class="block text-sm font-medium text-gray-700 mb-2">المتطلبات</label>
                    <textarea name="requirements" id="requirements" rows="3" 
                              placeholder="المستندات والمتطلبات المطلوبة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('requirements') }}</textarea>
                    @error('requirements')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="أي ملاحظات إضافية..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('services.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                                            <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-blue-100 rounded-lg font-medium hover:text-white">
                                إنشاء الخدمة
                            </button>
            </div>
        </form>
    </div>
</div>
@endsection

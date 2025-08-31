@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">تعديل مصدر العميل</h1>
        <p class="text-gray-600 mt-2">تعديل: {{ $clientSource->name }}</p>
    </div>

    <div class="max-w-2xl mx-auto">
        <form action="{{ route('client-sources.update', $clientSource) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المصدر *</label>
                    <input type="text" name="name" id="name" required 
                           value="{{ old('name', $clientSource->name) }}"
                           placeholder="مثال: واتساب، فيسبوك، موقع إلكتروني"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع المصدر *</label>
                    <select name="type" id="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر نوع المصدر</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type', $clientSource->type) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">الوصف</label>
                    <textarea name="description" id="description" rows="3" 
                              placeholder="وصف مختصر للمصدر..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $clientSource->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $clientSource->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">مصدر نشط</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('client-sources.show', $clientSource) }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تحديث المصدر
                </button>
            </div>
        </form>
    </div>
</div>
@endsection



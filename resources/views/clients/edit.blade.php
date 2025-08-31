@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">تعديل العميل</h1>
            <p class="text-gray-600 mt-2">{{ $client->name }}</p>
        </div>
        <a href="{{ route('clients.show', $client) }}" class="bg-blue-500 hover:bg-blue-600 text-blue-100 font-bold py-2 px-4 rounded">
            إلغاء
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
                            <div class="text-gray-900">
                    <form method="POST" action="{{ route('clients.update', $client) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Basic Information -->
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700">اسم العميل *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $client->name) }}" required
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="bride_name" class="block text-sm font-medium text-gray-700">اسم العروس</label>
                                    <input type="text" name="bride_name" id="bride_name" value="{{ old('bride_name', $client->bride_name) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="guardian_name" class="block text-sm font-medium text-gray-700">اسم ولي الأمر</label>
                                    <input type="text" name="guardian_name" id="guardian_name" value="{{ old('guardian_name', $client->guardian_name) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="space-y-4">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700">رقم الهاتف *</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $client->phone) }}" required
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">البريد الإلكتروني</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $client->email) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700">رقم الواتساب</label>
                                    <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $client->whatsapp_number) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>

                        <!-- Address and Status -->
                        <div class="mt-6 space-y-4">
                            <div>
                                <label for="address" class="block text-sm font-medium text-gray-700">العنوان</label>
                                <textarea name="address" id="address" rows="3"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('address', $client->address) }}</textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">الحالة *</label>
                                    <select name="status" id="status" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">اختر الحالة</option>
                                        <option value="new" {{ old('status', $client->status) == 'new' ? 'selected' : '' }}>جديد</option>
                                        <option value="in_progress" {{ old('status', $client->status) == 'in_progress' ? 'selected' : '' }}>قيد العمل</option>
                                        <option value="completed" {{ old('status', $client->status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="cancelled" {{ old('status', $client->status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_active" class="mr-2 block text-sm text-gray-900">نشط</label>
                                </div>
                            </div>
                        </div>

                        <!-- Service Selection -->
                        <div class="mt-6">
                            <label for="service_id" class="block text-sm font-medium text-gray-700">الخدمة المطلوبة</label>
                            <select name="service_id" id="service_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">اختر الخدمة</option>
                                @foreach($services as $service)
                                    <option value="{{ $service->id }}" {{ old('service_id', $client->service_id) == $service->id ? 'selected' : '' }}>
                                        {{ $service->name }} - {{ $service->price }} {{ $service->currency }}
                                    </option>
                                @endforeach
                            </select>
                            @error('service_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Client Source -->
                        <div class="mt-6">
                            <label for="source_id" class="block text-sm font-medium text-gray-700">مصدر العميل</label>
                            <select name="source_id" id="source_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <option value="">اختر المصدر</option>
                                @foreach($sources as $source)
                                    <option value="{{ $source->id }}" {{ old('source_id', $client->source_id) == $source->id ? 'selected' : '' }}>
                                        {{ $source->name }} ({{ $source->type_label }})
                                    </option>
                                @endforeach
                            </select>
                            @error('source_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes and Documents -->
                        <div class="mt-6 space-y-4">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">الملاحظات</label>
                                <textarea name="notes" id="notes" rows="4"
                                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('notes', $client->notes) }}</textarea>
                            </div>

                            <div>
                                <label for="documents" class="block text-sm font-medium text-gray-700">إضافة مستندات جديدة</label>
                                <input type="file" name="documents[]" id="documents" multiple
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">يمكنك اختيار أكثر من ملف</p>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3 space-x-reverse">
                            <a href="{{ route('clients.show', $client) }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-red-800 font-bold py-2 px-4 rounded">
                                إلغاء
                            </a>
                            <button type="submit" 
                                    class="bg-blue-600 hover:bg-blue-700 text-blue font-bold py-2 px-4 rounded">
                                حفظ التغييرات
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

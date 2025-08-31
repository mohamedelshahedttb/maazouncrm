@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('integrations.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            العودة إلى التكاملات
        </a>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">تعديل تكامل Facebook Messenger</h2>
                <p class="text-sm text-gray-600 mt-1">تعديل معلومات تكامل Facebook Messenger</p>
            </div>

            <form method="POST" action="{{ route('integrations.facebook.update', $facebook) }}" class="p-6">
                @csrf
                @method('PUT')

                <!-- Page Name -->
                <div class="mb-6">
                    <label for="page_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الصفحة</label>
                    <input type="text" name="page_name" id="page_name" value="{{ old('page_name', $facebook->page_name) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('page_name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Page ID -->
                <div class="mb-6">
                    <label for="page_id" class="block text-sm font-medium text-gray-700 mb-2">معرف الصفحة</label>
                    <input type="text" name="page_id" id="page_id" value="{{ old('page_id', $facebook->page_id) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('page_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Access Token -->
                <div class="mb-6">
                    <label for="access_token" class="block text-sm font-medium text-gray-700 mb-2">رمز الوصول</label>
                    <input type="text" name="access_token" id="access_token" value="{{ old('access_token', $facebook->access_token) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('access_token')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Webhook URL -->
                <div class="mb-6">
                    <label for="webhook_url" class="block text-sm font-medium text-gray-700 mb-2">رابط Webhook</label>
                    <input type="url" name="webhook_url" id="webhook_url" value="{{ old('webhook_url', $facebook->webhook_url) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="https://yourdomain.com/webhooks/facebook">
                    @error('webhook_url')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Verify Token -->
                <div class="mb-6">
                    <label for="verify_token" class="block text-sm font-medium text-gray-700 mb-2">رمز التحقق</label>
                    <input type="text" name="verify_token" id="verify_token" value="{{ old('verify_token', $facebook->verify_token) }}" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="maazoun_verify_token">
                    @error('verify_token')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- App Secret -->
                <div class="mb-6">
                    <label for="app_secret" class="block text-sm font-medium text-gray-700 mb-2">سر التطبيق</label>
                    <input type="text" name="app_secret" id="app_secret" value="{{ old('app_secret', $facebook->app_secret) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('app_secret')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status" id="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="active" {{ old('status', $facebook->status) == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status', $facebook->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="testing" {{ old('status', $facebook->status) == 'testing' ? 'selected' : '' }}>قيد الاختبار</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Is Active -->
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $facebook->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">نشط</span>
                    </label>
                </div>

                <!-- Notes -->
                <div class="mb-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $facebook->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Setup Instructions -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="text-lg font-medium text-blue-800 mb-3">تعليمات الإعداد</h3>
                    <div class="text-sm text-blue-700 space-y-2">
                        <p><strong>1.</strong> اذهب إلى <a href="https://developers.facebook.com" target="_blank" class="underline">Facebook Developers</a></p>
                        <p><strong>2.</strong> أنشئ تطبيق جديد أو استخدم تطبيق موجود</p>
                        <p><strong>3.</strong> أضف منتج Facebook Messenger</p>
                        <p><strong>4.</strong> احصل على معرف الصفحة ورمز الوصول</p>
                        <p><strong>5.</strong> اضبط Webhook URL على: <code class="bg-blue-100 px-2 py-1 rounded">{{ url('/webhooks/facebook') }}</code></p>
                        <p><strong>6.</strong> استخدم رمز التحقق: <code class="bg-blue-100 px-2 py-1 rounded">maazoun_verify_token</code></p>
                        <p><strong>7.</strong> تأكد من إضافة الصفحة إلى التطبيق</p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                    <a href="{{ route('integrations.index') }}"
                       class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50 transition-colors">
                        إلغاء
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">
                        تحديث التكامل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

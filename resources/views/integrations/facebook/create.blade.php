@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex items-center">
            <a href="{{ route('integrations.index') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-800">إضافة تكامل Facebook Messenger</h1>
                <p class="text-gray-600 mt-2">إعداد تكامل جديد مع Facebook Messenger API</p>
            </div>
        </div>
    </div>

    <div class="max-w-4xl">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800">معلومات التكامل</h2>
                <p class="text-sm text-gray-600 mt-1">أدخل معلومات تكامل Facebook Messenger الخاصة بك</p>
            </div>

            <form method="POST" action="{{ route('integrations.facebook.store') }}" class="p-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Page Name -->
                    <div class="col-span-2">
                        <label for="page_name" class="block text-sm font-medium text-gray-700 text-right mb-2">اسم الصفحة *</label>
                        <input type="text" name="page_name" id="page_name" value="{{ old('page_name') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم صفحة Facebook">
                        @error('page_name')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Page ID -->
                    <div>
                        <label for="page_id" class="block text-sm font-medium text-gray-700 text-right mb-2">معرف الصفحة *</label>
                        <input type="text" name="page_id" id="page_id" value="{{ old('page_id') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="123456789012345">
                        <p class="text-xs text-gray-500 mt-1 text-right">معرف صفحة Facebook (يمكن العثور عليه في إعدادات الصفحة)</p>
                        @error('page_id')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Access Token -->
                    <div>
                        <label for="access_token" class="block text-sm font-medium text-gray-700 text-right mb-2">رمز الوصول *</label>
                        <input type="text" name="access_token" id="access_token" value="{{ old('access_token') }}" required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="EAA...">
                        <p class="text-xs text-gray-500 mt-1 text-right">رمز الوصول الدائم للصفحة من Facebook Developer</p>
                        @error('access_token')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Webhook URL -->
                    <div class="col-span-2">
                        <label for="webhook_url" class="block text-sm font-medium text-gray-700 text-right mb-2">رابط Webhook</label>
                        <input type="url" name="webhook_url" id="webhook_url" value="{{ old('webhook_url') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://yourdomain.com/webhooks/facebook">
                        <p class="text-xs text-gray-500 mt-1 text-right">رابط Webhook لاستقبال الرسائل (اختياري)</p>
                        @error('webhook_url')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Verify Token -->
                    <div>
                        <label for="verify_token" class="block text-sm font-medium text-gray-700 text-right mb-2">رمز التحقق</label>
                        <input type="text" name="verify_token" id="verify_token" value="{{ old('verify_token', 'maazoun_verify_token') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="maazoun_verify_token">
                        <p class="text-xs text-gray-500 mt-1 text-right">رمز التحقق لـ Webhook</p>
                        @error('verify_token')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- App Secret -->
                    <div>
                        <label for="app_secret" class="block text-sm font-medium text-gray-700 text-right mb-2">سر التطبيق</label>
                        <input type="text" name="app_secret" id="app_secret" value="{{ old('app_secret') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل سر التطبيق">
                        <p class="text-xs text-gray-500 mt-1 text-right">سر التطبيق من Facebook Developer</p>
                        @error('app_secret')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 text-right mb-2">الحالة *</label>
                        <select name="status" id="status" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر الحالة</option>
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                            <option value="pending_verification" {{ old('status') == 'pending_verification' ? 'selected' : '' }}>في انتظار التحقق</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Is Active -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="is_active" class="mr-2 block text-sm text-gray-700">تفعيل التكامل فوراً</label>
                    </div>

                    <!-- Notes -->
                    <div class="col-span-2">
                        <label for="notes" class="block text-sm font-medium text-gray-700 text-right mb-2">ملاحظات</label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="أي ملاحظات إضافية حول التكامل">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-red-500 text-sm mt-1 text-right">{{ $message }}</p>
                        @enderror
                    </div>
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
                        إنشاء التكامل
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

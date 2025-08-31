@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إضافة محادثة جديدة</h1>
        <p class="text-gray-600 mt-2">إضافة محادثة جديدة مع العميل</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('conversations.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">العميل *</label>
                    <select name="client_id" id="client_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر العميل</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} - {{ $client->phone }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">نوع المحادثة *</label>
                    <select name="type" id="type" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر النوع</option>
                        <option value="phone" {{ old('type') == 'phone' ? 'selected' : '' }}>هاتف</option>
                        <option value="whatsapp" {{ old('type') == 'whatsapp' ? 'selected' : '' }}>واتساب</option>
                        <option value="email" {{ old('type') == 'email' ? 'selected' : '' }}>بريد إلكتروني</option>
                        <option value="in_person" {{ old('type') == 'in_person' ? 'selected' : '' }}>شخصياً</option>
                        <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>أخرى</option>
                    </select>
                </div>

                <div>
                    <label for="direction" class="block text-sm font-medium text-gray-700 mb-2">اتجاه المحادثة *</label>
                    <select name="direction" id="direction" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الاتجاه</option>
                        <option value="incoming" {{ old('direction') == 'incoming' ? 'selected' : '' }}>وارد</option>
                        <option value="outgoing" {{ old('direction') == 'outgoing' ? 'selected' : '' }}>صادر</option>
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة *</label>
                    <select name="status" id="status" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الحالة</option>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>محلول</option>
                        <option value="pending_followup" {{ old('status') == 'pending_followup' ? 'selected' : '' }}>في انتظار المتابعة</option>
                    </select>
                </div>

                <div>
                    <label for="conversation_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ المحادثة *</label>
                    <input type="datetime-local" name="conversation_date" id="conversation_date" required 
                           value="{{ old('conversation_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="phone_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="phone_number" id="phone_number" 
                           value="{{ old('phone_number') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الواتساب</label>
                    <input type="text" name="whatsapp_number" id="whatsapp_number" 
                           value="{{ old('whatsapp_number') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="email_address" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email_address" id="email_address" 
                           value="{{ old('email_address') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">محتوى المحادثة *</label>
                    <textarea name="content" id="content" rows="4" required
                              placeholder="تفاصيل المحادثة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('content') }}</textarea>
                </div>

                <div class="md:col-span-2">
                    <label for="summary" class="block text-sm font-medium text-gray-700 mb-2">ملخص المحادثة</label>
                    <textarea name="summary" id="summary" rows="3" 
                              placeholder="ملخص مختصر للمحادثة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('summary') }}</textarea>
                </div>

                <div>
                    <label for="follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ المتابعة</label>
                    <input type="date" name="follow_up_date" id="follow_up_date" 
                           value="{{ old('follow_up_date') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="md:col-span-2">
                    <label for="follow_up_notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات المتابعة</label>
                    <textarea name="follow_up_notes" id="follow_up_notes" rows="3" 
                              placeholder="ملاحظات للمتابعة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('follow_up_notes') }}</textarea>
                </div>
            </div>

            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('conversations.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    إضافة المحادثة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

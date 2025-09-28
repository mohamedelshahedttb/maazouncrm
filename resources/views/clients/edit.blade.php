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

                                <div>
                                    <label for="geographical_area" class="block text-sm font-medium text-gray-700">المنطقة الجغرافية</label>
                                    <input type="text" name="geographical_area" id="geographical_area" value="{{ old('geographical_area', $client->geographical_area) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="governorate" class="block text-sm font-medium text-gray-700">المحافظة</label>
                                    <input type="text" name="governorate" id="governorate" value="{{ old('governorate', $client->governorate) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="area" class="block text-sm font-medium text-gray-700">المنطقة</label>
                                    <input type="text" name="area" id="area" value="{{ old('area', $client->area) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="google_maps_link" class="block text-sm font-medium text-gray-700">رابط الموقع من خرائط جوجل</label>
                                    <input type="url" name="google_maps_link" id="google_maps_link" value="{{ old('google_maps_link', $client->google_maps_link) }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div>
                                    <label for="relationship_status" class="block text-sm font-medium text-gray-700">صلة القرابة ولي العروسة</label>
                                    <input type="text" name="relationship_status" id="relationship_status" value="{{ old('relationship_status', $client->relationship_status) }}"
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
                                    <label for="client_status" class="block text-sm font-medium text-gray-700">الحالة *</label>
                                    <select name="client_status" id="client_status" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">اختر الحالة</option>
                                        <option value="new" {{ old('client_status', $client->client_status) == 'new' ? 'selected' : '' }}>جديد</option>
                                        <option value="in_progress" {{ old('client_status', $client->client_status) == 'in_progress' ? 'selected' : '' }}>قيد العمل</option>
                                        <option value="completed" {{ old('client_status', $client->client_status) == 'completed' ? 'selected' : '' }}>مكتمل</option>
                                        <option value="cancelled" {{ old('client_status', $client->client_status) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                    </select>
                                    @error('client_status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="is_active" class="mr-2 block text-sm text-gray-900">نشط</label>
                                </div>
                            </div>

                            <!-- Call Result and Follow-up -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="call_result" class="block text-sm font-medium text-gray-700">نتيجة المكالمة</label>
                                    <select name="call_result" id="call_result" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">اختر نتيجة المكالمة</option>
                                        <option value="interested" {{ old('call_result', $client->call_result) == 'interested' ? 'selected' : '' }}>مهتم</option>
                                        <option value="not_interested" {{ old('call_result', $client->call_result) == 'not_interested' ? 'selected' : '' }}>غير مهتم</option>
                                        <option value="follow_up_later" {{ old('call_result', $client->call_result) == 'follow_up_later' ? 'selected' : '' }}>متابعة لاحقا</option>
                                        <option value="potential_client" {{ old('call_result', $client->call_result) == 'potential_client' ? 'selected' : '' }}>عميل محتمل</option>
                                        <option value="confirmed_booking" {{ old('call_result', $client->call_result) == 'confirmed_booking' ? 'selected' : '' }}>حجز مؤكد</option>
                                        <option value="completed_booking" {{ old('call_result', $client->call_result) == 'completed_booking' ? 'selected' : '' }}>حجز مكتمل</option>
                                        <option value="cancelled" {{ old('call_result', $client->call_result) == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                                        <option value="inquiry" {{ old('call_result', $client->call_result) == 'inquiry' ? 'selected' : '' }}>استفسار</option>
                                        <option value="client_booking" {{ old('call_result', $client->call_result) == 'client_booking' ? 'selected' : '' }}>حجز العميل</option>
                                        <option value="no_answer" {{ old('call_result', $client->call_result) == 'no_answer' ? 'selected' : '' }}>لم يتم الرد</option>
                                        <option value="busy_number" {{ old('call_result', $client->call_result) == 'busy_number' ? 'selected' : '' }}>الرقم مشغول</option>
                                    </select>
                                </div>

                                <div>
                                    <label for="next_follow_up_date" class="block text-sm font-medium text-gray-700">تاريخ المتابعة التالية</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="next_follow_up_date" id="next_follow_up_date" value="{{ old('next_follow_up_date', $client->next_follow_up_date?->format('d/m/Y')) }}"
                                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                               placeholder="dd/mm/yyyy">
                                        <input type="date" id="next_follow_up_date_calendar"
                                               class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    </div>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Date picker functionality
    const dateInput = document.getElementById('next_follow_up_date');
    const calendarInput = document.getElementById('next_follow_up_date_calendar');

    if (dateInput && calendarInput) {
        // Calendar to text conversion
        calendarInput.addEventListener('change', function() {
            if (this.value) {
                const date = new Date(this.value);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const year = date.getFullYear();
                dateInput.value = `${day}/${month}/${year}`;
            }
        });

        // Text to calendar conversion
        dateInput.addEventListener('blur', function() {
            if (this.value && this.value.match(/^\d{2}\/\d{2}\/\d{4}$/)) {
                const [day, month, year] = this.value.split('/');
                const date = new Date(year, month - 1, day);
                if (!isNaN(date.getTime())) {
                    calendarInput.value = date.toISOString().split('T')[0];
                }
            }
        });
    }
});
</script>
@endsection

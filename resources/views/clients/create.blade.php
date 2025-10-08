@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إضافة عميل جديد</h1>
        <p class="text-gray-600 mt-2">املأ التفاصيل لتسجيل عميل جديد في النظام</p>
    </div>

    <!-- Client Form -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">معلومات العميل</h3>
        </div>
        
        <form method="POST" action="{{ route('clients.store') }}" enctype="multipart/form-data" class="p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Left Column -->
                <div class="space-y-6">
                    <!-- Client Name (Groom) -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم العميل *</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم العميل">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Service Selection (required for pricing) -->
                    <div>
                        <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">الخدمة المطلوبة</label>
                        <select name="service_id" id="service_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر الخدمة</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                    {{ $service->name }} - {{ number_format($service->price, 2) }} {{ $service->currency }}
                                </option>
                            @endforeach
                        </select>
                        @error('service_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client Source -->
                    <div>
                        <label for="source_id" class="block text-sm font-medium text-gray-700 mb-2">مصدر العميل</label>
                        <select name="source_id" id="source_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر المصدر</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}" {{ old('source_id') == $source->id ? 'selected' : '' }}>
                                    {{ $source->name }} ({{ $source->type_label }})
                                </option>
                            @endforeach
                        </select>
                        @error('source_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Date -->
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ العقد</label>
                        <div class="flex gap-2">
                            <input type="text" name="event_date" id="event_date" value="{{ old('event_date') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="dd/mm/yyyy">
                            <input type="date" id="event_date_calendar" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        @error('event_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bride Residence -->
                    <div>
                        <label for="bride_id_address" class="block text-sm font-medium text-gray-700 mb-2">محل إقامة الزوجة</label>
                        <textarea name="bride_id_address" id="bride_id_address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="أدخل محل إقامة الزوجة">{{ old('bride_id_address') }}</textarea>
                        @error('bride_id_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Mahr Amount -->
                    <div>
                        <label for="mahr" class="block text-sm font-medium text-gray-700 mb-2">مبلغ المؤخر</label>
                        <input type="text" name="mahr" id="mahr" value="{{ old('mahr') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل مبلغ المؤخر">
                        @error('mahr')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Governorate Selection for Pricing -->
                    <div>
                        <label for="governorate_id" class="block text-sm font-medium text-gray-700 mb-2">المحافظة (طريقة تسعير خاصة)</label>
                        @php
                          $governorates = \Illuminate\Support\Facades\Schema::hasTable('governorates')
                            ? \App\Models\Governorate::where('is_active', true)->orderBy('name')->get()
                            : collect();
                        @endphp
                        <select name="governorate_id" id="governorate_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر المحافظة</option>
                            @foreach($governorates as $gov)
                                <option value="{{ $gov->id }}" {{ old('governorate_id') == $gov->id ? 'selected' : '' }}>
                                    {{ $gov->name }} - ثابت: {{ number_format($gov->base_fixed_fee, 2) }} + إضافي: {{ number_format($gov->added_fees, 2) }} @if($gov->mahr_percentage) | نسبة مؤخر: {{ rtrim(rtrim(number_format($gov->mahr_percentage, 2), '0'), '.') }}%@endif
                                </option>
                            @endforeach
                        </select>
                        @if(!\Illuminate\Support\Facades\Schema::hasTable('governorates'))
                          <p class="text-xs text-gray-500 mt-1">سيظهر الاختيار هنا بعد تشغيل الترحيلات.</p>
                        @endif
                    </div>

                    <!-- Area Selection for Pricing -->
                    <div>
                        <label for="area_id" class="block text-sm font-medium text-gray-700 mb-2">المنطقة (لحساب السعر)</label>
                        <select name="area_id" id="area_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر المنطقة</option>
                            @foreach(\App\Models\Area::where('is_active', true)->orderBy('name')->get() as $area)
                                <option value="{{ $area->id }}" {{ old('area_id') == $area->id ? 'selected' : '' }}>
                                    {{ $area->name }} - مواصلات: {{ number_format($area->transportation_fee, 2) }}
                                    @if($area->mahr_percentage) | نسبة مؤخر: {{ rtrim(rtrim(number_format($area->mahr_percentage, 2), '0'), '.') }}%@endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Contract Location -->
                    <div>
                        <label for="contract_location" class="block text-sm font-medium text-gray-700 mb-2">مكان العقد</label>
                        <input type="text" name="contract_location" id="contract_location" value="{{ old('contract_location') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل مكان العقد">
                        @error('contract_location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Auto Calculated Price -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">سعر الخدمة (محسوب تلقائياً)</label>
                        <div class="flex items-center gap-3">
                            <input type="text" id="calculated_price" readonly
                                   class="w-full px-3 py-2 border border-gray-300 bg-gray-50 rounded-lg focus:outline-none"
                                   value="">
                            <button type="button" id="recalculate_price" class="px-4 py-2 bg-green-600 text-white rounded-lg">حساب</button>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">الحساب = مواصلات المنطقة + نسبة من المؤخر (إن وجدت) + تعريفة المؤخر حسب السياسة</p>
                    </div>


                    <!-- Guardian Relationship -->
                    <div>
                        <label for="relationship_status" class="block text-sm font-medium text-gray-700 mb-2">صلة قرابة الولي</label>
                        <input type="text" name="relationship_status" id="relationship_status" value="{{ old('relationship_status') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: والد، أخ، عم، خال">
                        @error('relationship_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bride Age -->
                    <div>
                        <label for="bride_age" class="block text-sm font-medium text-gray-700 mb-2">سن الزوجة</label>
                        <input type="number" name="bride_age" id="bride_age" value="{{ old('bride_age') }}" min="1" max="100"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل سن الزوجة">
                        @error('bride_age')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Accessories -->
                    <div>
                        <label for="accessories" class="block text-sm font-medium text-gray-700 mb-2">اكسسوارات العقد</label>
                        <select name="accessories[]" id="accessories" multiple
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->selling_price }}" {{ in_array($product->id, old('accessories', [])) ? 'selected' : '' }}>
                                    {{ $product->name }} - {{ number_format($product->selling_price, 2) }} {{ $product->currency }}
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">اضغط Ctrl للاختيار المتعدد</p>
                        @error('accessories')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Follow-up Date -->
                    <div>
                        <label for="next_follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">موعد المتابعة</label>
                        <div class="flex gap-2">
                            <input type="text" name="next_follow_up_date" id="next_follow_up_date" value="{{ old('next_follow_up_date') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="dd/mm/yyyy">
                            <input type="date" id="next_follow_up_date_calendar" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        @error('next_follow_up_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client Status -->
                    <div>
                        <label for="client_status" class="block text-sm font-medium text-gray-700 mb-2">حالة العميل</label>
                        <select name="client_status" id="client_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="new" {{ old('client_status', 'new') == 'new' ? 'selected' : '' }}>جديد</option>
                            <option value="in_progress" {{ old('client_status') == 'in_progress' ? 'selected' : '' }}>جاري العمل عليه</option>
                            <option value="completed" {{ old('client_status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                            <option value="cancelled" {{ old('client_status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                        </select>
                        @error('client_status')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Discount Type -->
                    <div>
                        <label for="discount_type" class="block text-sm font-medium text-gray-700 mb-2">نسبة الخصم</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <select name="discount_type" id="discount_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">اختر نوع الخصم</option>
                                    <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>نسبة مئوية</option>
                                    <option value="fixed_amount" {{ old('discount_type') == 'fixed_amount' ? 'selected' : '' }}>مبلغ ثابت</option>
                                </select>
                            </div>
                            <div>
                                <input type="number" name="discount_value" id="discount_value" value="{{ old('discount_value') }}" step="0.01" min="0"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                       placeholder="قيمة الخصم">
                            </div>
                        </div>
                        @error('discount_type')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        @error('discount_value')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
                    <!-- WhatsApp Number -->
                    <div>
                        <label for="whatsapp_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الواتساب</label>
                        <input type="tel" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 05XXXXXXXXX">
                        @error('whatsapp_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 05XXXXXXXXX">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Groom Name -->
                    <div>
                        <label for="groom_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الزوج</label>
                        <input type="text" name="groom_name" id="groom_name" value="{{ old('groom_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم الزوج">
                        @error('groom_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Bride Name -->
                    <div>
                        <label for="bride_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الزوجة</label>
                        <input type="text" name="bride_name" id="bride_name" value="{{ old('bride_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم الزوجة">
                        @error('bride_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Address -->
                    <div>
                        <label for="contract_address" class="block text-sm font-medium text-gray-700 mb-2">عنوان العقد بالتفصيل</label>
                        <textarea name="contract_address" id="contract_address" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="أدخل عنوان العقد بالتفصيل">{{ old('contract_address') }}</textarea>
                        @error('contract_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Google Maps Link -->
                    <div>
                        <label for="google_maps_link" class="block text-sm font-medium text-gray-700 mb-2">رابط الموقع من خرائط جوجل</label>
                        <input type="url" name="google_maps_link" id="google_maps_link" value="{{ old('google_maps_link') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="https://maps.google.com/...">
                        @error('google_maps_link')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Temporary Document -->
                    <div>
                        <label for="temporary_document" class="block text-sm font-medium text-gray-700 mb-2">الوثيقة المؤقتة</label>
                        <input type="text" name="temporary_document" id="temporary_document" value="{{ old('temporary_document') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل الوثيقة المؤقتة">
                        @error('temporary_document')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Sheikh Name -->
                    <div>
                        <label for="sheikh_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الشيخ</label>
                        <input type="text" name="sheikh_name" id="sheikh_name" value="{{ old('sheikh_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم الشيخ">
                        @error('sheikh_name')
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
                        <label for="document_number" class="block text-sm font-medium text-gray-700 mb-2">رقم الوثيقة</label>
                        <input type="text" name="document_number" id="document_number" value="{{ old('document_number') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل رقم الوثيقة">
                        @error('document_number')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contract Executor -->
                    <div>
                        <label for="contract_executor" class="block text-sm font-medium text-gray-700 mb-2">منفذ العقد</label>
                        <input type="text" name="contract_executor" id="contract_executor" value="{{ old('contract_executor') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم منفذ العقد">
                        @error('contract_executor')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Coupon Arrival Date -->
                    <div>
                        <label for="coupon_arrival_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ وصول القسيمة</label>
                        <div class="flex gap-2">
                            <input type="text" name="coupon_arrival_date" id="coupon_arrival_date" value="{{ old('coupon_arrival_date') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="dd/mm/yyyy">
                            <input type="date" id="coupon_arrival_date_calendar" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        @error('coupon_arrival_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Receipt Date -->
                    <div>
                        <label for="document_receipt_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ استلام الوثيقة</label>
                        <div class="flex gap-2">
                            <input type="text" name="document_receipt_date" id="document_receipt_date" value="{{ old('document_receipt_date') }}"
                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="dd/mm/yyyy">
                            <input type="date" id="document_receipt_date_calendar" 
                                   class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                        @error('document_receipt_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Receiver -->
                    <div>
                        <label for="document_receiver" class="block text-sm font-medium text-gray-700 mb-2">مستلم الوثيقة</label>
                        <select name="document_receiver" id="document_receiver" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">اختر مستلم الوثيقة</option>
                            <option value="delivery" {{ old('document_receiver') == 'delivery' ? 'selected' : '' }}>دليفري</option>
                            <option value="client" {{ old('document_receiver') == 'client' ? 'selected' : '' }}>العميل</option>
                            <option value="client_relative" {{ old('document_receiver') == 'client_relative' ? 'selected' : '' }}>أحد أقارب العميل</option>
                        </select>
                        @error('document_receiver')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Delivery Man Name (conditional) -->
                    <div id="delivery_man_field" style="display: none;">
                        <label for="delivery_man_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الدليفري</label>
                        <input type="text" name="delivery_man_name" id="delivery_man_name" value="{{ old('delivery_man_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم الدليفري">
                        @error('delivery_man_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client Relative Name (conditional) -->
                    <div id="client_relative_field" style="display: none;">
                        <label for="client_relative_name" class="block text-sm font-medium text-gray-700 mb-2">اسم قريب العميل</label>
                        <input type="text" name="client_relative_name" id="client_relative_name" value="{{ old('client_relative_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم قريب العميل">
                        @error('client_relative_name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Client Receiver Name (conditional) -->
                    <div id="client_receiver_field" style="display: none;">
                        <label for="client_receiver_name" class="block text-sm font-medium text-gray-700 mb-2">ادخل اسم العميل المستلم</label>
                        <input type="text" name="client_receiver_name" id="client_receiver_name" value="{{ old('client_receiver_name') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل اسم المستلم">
                        @error('client_receiver_name')
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

            <!-- Documents -->
            <div class="mt-6">
                <label for="documents" class="block text-sm font-medium text-gray-700 mb-2">المستندات</label>
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                    </svg>
                    <p class="text-gray-600 mb-2">اسحب وأفلت الملفات هنا، أو</p>
                    <input type="file" name="documents[]" id="documents" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="hidden">
                    <label for="documents" class="inline-flex items-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 cursor-pointer">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        اختيار الملفات
                    </label>
                    <p class="text-sm text-gray-500 mt-2">PDF, DOC, DOCX, JPG, PNG (الحد الأقصى: 10 ملفات)</p>
                </div>
                @error('documents')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Final Price Display -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-blue-800">السعر النهائي</h3>
                    <div class="text-right">
                        <input type="hidden" name="final_price" id="final_price" value="{{ old('final_price') }}">
                        <div id="final_price_display" class="text-2xl font-bold text-blue-900">0.00 جنيه</div>
                        <p class="text-sm text-blue-600">السعر النهائي بعد الخصم</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('clients.index') }}" class="px-6 py-2 border border-gray-300 text-red-700 rounded-lg hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-500 text-blue-100 rounded-lg hover:bg-blue-600 hover:text-blue font-medium">
                    حفظ العميل +
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// File upload preview
document.getElementById('documents').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const container = e.target.parentElement;
    
    // Remove existing preview
    const existingPreview = container.querySelector('.file-preview');
    if (existingPreview) {
        existingPreview.remove();
    }
    
    if (files.length > 0) {
        const preview = document.createElement('div');
        preview.className = 'file-preview mt-4';
        preview.innerHTML = '<p class="text-sm font-medium text-gray-700 mb-2">الملفات المختارة:</p>';
        
        files.forEach(file => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-gray-50 rounded mb-2';
            fileItem.innerHTML = `
                <span class="text-sm text-gray-600">${file.name}</span>
                <span class="text-xs text-gray-500">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
            `;
            preview.appendChild(fileItem);
        });
        
        container.appendChild(preview);
    }
});
</script>
<script>
// Auto pricing
function parseNumber(value) {
  if (!value) return null;
  const n = parseFloat(String(value).replace(/[^0-9.]/g, ''));
  return isNaN(n) ? null : n;
}

async function recalcPrice() {
  const serviceIdEl = document.getElementById('service_id');
  const areaIdEl = document.getElementById('area_id');
  const governorateIdEl = document.getElementById('governorate_id');
  const mahrEl = document.getElementById('mahr');
  const priceEl = document.getElementById('calculated_price');
  if (!serviceIdEl) return;
  const serviceId = serviceIdEl.value ? parseInt(serviceIdEl.value) : null;
  const governorateId = governorateIdEl && governorateIdEl.value ? parseInt(governorateIdEl.value) : null;
  const areaId = (!governorateId && areaIdEl && areaIdEl.value) ? parseInt(areaIdEl.value) : null; // ignore area if governorate is selected
  const mahr = parseNumber(mahrEl && mahrEl.value ? mahrEl.value : null);
  if (!serviceId && !governorateId && !areaId) {
    priceEl.value = '';
    calculateFinalPrice();
    return;
  }
  try {
    const res = await fetch("{{ route('pricing.calculate') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
      },
      body: JSON.stringify({ service_id: serviceId, area_id: areaId, governorate_id: governorateId, mahr: mahr })
    });
    const data = await res.json();
    if (data && typeof data.price !== 'undefined') {
      priceEl.value = data.price.toFixed(2) + ' {{ config('app.currency', 'EGP') }}';
      calculateFinalPrice();
    }
  } catch (e) { /* ignore */ }
}

function calculateFinalPrice() {
  const calculatedPriceEl = document.getElementById('calculated_price');
  const discountTypeEl = document.getElementById('discount_type');
  const discountValueEl = document.getElementById('discount_value');
  const accessoriesEl = document.getElementById('accessories');
  const finalPriceEl = document.getElementById('final_price');
  const finalPriceDisplayEl = document.getElementById('final_price_display');
  
  const basePrice = parseNumber(calculatedPriceEl.value) || 0;

  let accessoriesTotal = 0;
  if (accessoriesEl) {
    for (const option of accessoriesEl.selectedOptions) {
      const price = parseNumber(option.dataset.price);
      if (price) {
        accessoriesTotal += price;
      }
    }
  }

  const priceAfterAccessories = basePrice + accessoriesTotal;
  
  let finalPrice = priceAfterAccessories;
  
  if (discountTypeEl && discountTypeEl.value && discountValueEl && discountValueEl.value) {
    const discountValue = parseNumber(discountValueEl.value);
    if (discountValue) {
      if (discountTypeEl.value === 'percentage') {
        finalPrice = priceAfterAccessories - (priceAfterAccessories * discountValue / 100);
      } else if (discountTypeEl.value === 'fixed_amount') {
        finalPrice = priceAfterAccessories - discountValue;
      }
    }
  }
  
  finalPrice = Math.max(0, finalPrice); // Ensure price is not negative
  finalPriceEl.value = finalPrice.toFixed(2);
  finalPriceDisplayEl.textContent = finalPrice.toFixed(2) + ' جنيه';
}

// Date formatting for dd/mm/yyyy
function formatDateInput(input) {
  input.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length >= 2) {
      value = value.substring(0, 2) + '/' + value.substring(2);
    }
    if (value.length >= 5) {
      value = value.substring(0, 5) + '/' + value.substring(5, 9);
    }
    e.target.value = value;
  });
}

// Initialize date formatting and calendar functionality
document.addEventListener('DOMContentLoaded', function() {
  const dateInputs = ['event_date', 'next_follow_up_date', 'coupon_arrival_date', 'document_receipt_date'];
  dateInputs.forEach(id => {
    const input = document.getElementById(id);
    const calendarInput = document.getElementById(id + '_calendar');
    
    if (input) formatDateInput(input);
    
    // Calendar to text conversion
    if (calendarInput) {
      calendarInput.addEventListener('change', function() {
        if (this.value) {
          const date = new Date(this.value);
          const day = String(date.getDate()).padStart(2, '0');
          const month = String(date.getMonth() + 1).padStart(2, '0');
          const year = date.getFullYear();
          input.value = `${day}/${month}/${year}`;
        }
      });
    }
  });

  // Document receiver conditional fields
  const documentReceiver = document.getElementById('document_receiver');
  const deliveryManField = document.getElementById('delivery_man_field');
  const clientRelativeField = document.getElementById('client_relative_field');
  const clientReceiverField = document.getElementById('client_receiver_field');

  function toggleConditionalFields() {
    const value = documentReceiver.value;
    
    // Hide all conditional fields
    deliveryManField.style.display = 'none';
    clientRelativeField.style.display = 'none';
    clientReceiverField.style.display = 'none';
    
    // Show relevant field based on selection
    if (value === 'delivery') {
      deliveryManField.style.display = 'block';
    } else if (value === 'client_relative') {
      clientRelativeField.style.display = 'block';
    } else if (value === 'client') {
      clientReceiverField.style.display = 'block';
    }
  }

  if (documentReceiver) {
    documentReceiver.addEventListener('change', toggleConditionalFields);
    // Initialize on page load
    toggleConditionalFields();
  }
});

document.getElementById('recalculate_price')?.addEventListener('click', recalcPrice);
document.getElementById('area_id')?.addEventListener('change', recalcPrice);
document.getElementById('mahr')?.addEventListener('input', recalcPrice);
document.getElementById('governorate_id')?.addEventListener('change', function() {
  // Disable area when governorate selected, enable otherwise
  const govSelected = !!this.value;
  const areaSelect = document.getElementById('area_id');
  if (areaSelect) {
    areaSelect.disabled = govSelected;
    if (govSelected) {
      areaSelect.value = '';
    }
  }
  recalcPrice();
});
document.getElementById('service_id')?.addEventListener('change', recalcPrice);
document.getElementById('discount_type')?.addEventListener('change', calculateFinalPrice);
document.getElementById('discount_value')?.addEventListener('input', calculateFinalPrice);
document.getElementById('accessories')?.addEventListener('change', calculateFinalPrice);
</script>
@endsection
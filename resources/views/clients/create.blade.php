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
                        <input type="date" name="event_date" id="event_date" value="{{ old('event_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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

                    <!-- Contract Cost -->
                    <div>
                        <label for="contract_cost" class="block text-sm font-medium text-gray-700 mb-2">تكلفة العقد</label>
                        <input type="number" name="contract_cost" id="contract_cost" value="{{ old('contract_cost') }}" step="0.01" min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="أدخل تكلفة العقد">
                        @error('contract_cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
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

                    <!-- Follow-up Date -->
                    <div>
                        <label for="next_follow_up_date" class="block text-sm font-medium text-gray-700 mb-2">موعد المتابعة</label>
                        <input type="date" name="next_follow_up_date" id="next_follow_up_date" value="{{ old('next_follow_up_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('next_follow_up_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column -->
                <div class="space-y-6">
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
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف *</label>
                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                               placeholder="مثل: 05XXXXXXXXX">
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Groom Name -->
                    <div>
                        <label for="groom_name" class="block text-sm font-medium text-gray-700 mb-2">اسم الزوج *</label>
                        <input type="text" name="groom_name" id="groom_name" value="{{ old('groom_name') }}" required
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

                    <!-- Coupon Arrival Date -->
                    <div>
                        <label for="coupon_arrival_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ وصول القسيمة</label>
                        <input type="date" name="coupon_arrival_date" id="coupon_arrival_date" value="{{ old('coupon_arrival_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('coupon_arrival_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Document Receipt Date -->
                    <div>
                        <label for="document_receipt_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ استلام الوثيقة</label>
                        <input type="date" name="document_receipt_date" id="document_receipt_date" value="{{ old('document_receipt_date') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
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
  const mahrEl = document.getElementById('mahr');
  const priceEl = document.getElementById('calculated_price');
  if (!serviceIdEl) return;
  const serviceId = serviceIdEl.value ? parseInt(serviceIdEl.value) : null;
  const areaId = areaIdEl && areaIdEl.value ? parseInt(areaIdEl.value) : null;
  const mahr = parseNumber(mahrEl && mahrEl.value ? mahrEl.value : null);
  if (!serviceId) { priceEl.value = ''; return; }
  try {
    const res = await fetch("{{ route('pricing.calculate') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
      },
      body: JSON.stringify({ service_id: serviceId, area_id: areaId, mahr: mahr })
    });
    const data = await res.json();
    if (data && typeof data.price !== 'undefined') {
      priceEl.value = data.price.toFixed(2) + ' {{ config('app.currency', 'EGP') }}';
    }
  } catch (e) { /* ignore */ }
}

document.getElementById('recalculate_price')?.addEventListener('click', recalcPrice);
document.getElementById('area_id')?.addEventListener('change', recalcPrice);
document.getElementById('mahr')?.addEventListener('input', recalcPrice);
document.getElementById('service_id')?.addEventListener('change', recalcPrice);
</script>
@endsection
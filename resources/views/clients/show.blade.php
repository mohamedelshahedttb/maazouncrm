@extends('layouts.app')

@section('content')
<div class="p-6 bg-gray-50 min-h-screen">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">تفاصيل العميل</h1>
            <p class="text-gray-600 mt-2">عرض جميع المعلومات المسجلة للعميل: {{ $client->name }}</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('clients.edit', $client) }}" class="bg-blue-500 hover:bg-blue-600 text-blue-100 px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل العميل
            </a>
            <a href="{{ route('clients.print', $client) }}" target="_blank" class="bg-indigo-600 hover:bg-indigo-700 text-blue-100 px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8V4a1 1 0 011-1h8a1 1 0 011 1v4m-1 8h2a2 2 0 002-2V9a2 2 0 00-2-2h-1M5 16h10v5H5a2 2 0 01-2-2v-1a2 2 0 012-2z"></path>
                </svg>
                طباعة / PDF
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-blue-100 px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                عودة للقائمة
            </a>
        </div>
    </div>

    <!-- Client Details Card -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <!-- Card Header -->
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-gray-800">المعلومات الكاملة</h3>
            <span class="px-3 py-1 text-sm font-medium rounded-full {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $client->is_active ? 'نشط' : 'غير نشط' }}
            </span>
        </div>

        <!-- Card Body -->
        <div class="p-6">
            <!-- Section Title -->
            <h4 class="text-md font-bold text-gray-700 mb-4 border-b pb-2">المعلومات الأساسية</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="info-item">
                    <label>اسم العميل</label>
                    <p>{{ $client->name }}</p>
                </div>
                <div class="info-item">
                    <label>مصدر العميل</label>
                    <p>{{ $client->source->name ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>تاريخ العقد</label>
                    <p>{{ $client->event_date?->format('d/m/Y') ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>اسم الزوج</label>
                    <p>{{ $client->groom_name ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>اسم الزوجة</label>
                    <p>{{ $client->bride_name ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>سن الزوجة</label>
                    <p>{{ $client->bride_age ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>صلة قرابة الولي</label>
                    <p>{{ $client->relationship_status ?? '-' }}</p>
                </div>
                <div class="info-item col-span-1 md:col-span-2">
                    <label>محل إقامة الزوجة</label>
                    <p>{{ $client->bride_id_address ?? '-' }}</p>
                </div>
            </div>

            <!-- Section Title -->
            <h4 class="text-md font-bold text-gray-700 mt-8 mb-4 border-b pb-2">معلومات التواصل والموقع</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="info-item">
                    <label>رقم الهاتف</label>
                    <p>{{ $client->phone }}</p>
                </div>
                <div class="info-item">
                    <label>رقم الواتساب</label>
                    <p>{{ $client->whatsapp_number ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>مكان العقد</label>
                    <p>{{ $client->contract_location ?? '-' }}</p>
                </div>
                <div class="info-item col-span-1 md:col-span-2">
                    <label>عنوان العقد بالتفصيل</label>
                    <p>{{ $client->contract_address ?? '-' }}</p>
                </div>
                <div class="info-item col-span-1 md:col-span-3">
                    <label>رابط الموقع (خرائط جوجل)</label>
                    <p>
                        @if($client->google_maps_link)
                            <a href="{{ $client->google_maps_link }}" target="_blank" class="text-blue-600 hover:underline">{{ $client->google_maps_link }}</a>
                        @else
                            -
                        @endif
                    </p>
                </div>
            </div>

            <!-- Section Title -->
            <h4 class="text-md font-bold text-gray-700 mt-8 mb-4 border-b pb-2">التسعير والخدمات</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="info-item">
                    <label>الخدمة المطلوبة</label>
                    <p>{{ $client->service->name ?? '-' }}</p>
                </div>
                 <div class="info-item">
                    <label>المحافظة (إن وجدت)</label>
                    <p>{{ $client->governorate->name ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>المنطقة</label>
                    <p>{{ $client->area->name ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>مبلغ المؤخر</label>
                    <p>{{ number_format($client->mahr, 2) ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>الخصم</label>
                    <p>
                        @if($client->discount_value)
                            {{ $client->discount_value }} {{ $client->discount_type == 'percentage' ? '%' : 'جنيه' }}
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="info-item bg-blue-50 p-3 rounded-lg">
                    <label class="text-blue-800">السعر النهائي</label>
                    <p class="text-xl font-bold text-blue-900">{{ number_format($client->final_price, 2) ?? '0.00' }} جنيه</p>
                </div>
                <div class="info-item col-span-1 md:col-span-3">
                    <label>اكسسوارات العقد</label>
                    <p>
                        @forelse($client->accessories ?? [] as $productId)
                            @php
                                $product = \App\Models\Product::find($productId);
                            @endphp
                            @if($product)
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded">{{ $product->name }}</span>
                            @endif
                        @empty
                            -
                        @endforelse
                    </p>
                </div>
            </div>
            
            <!-- Section Title -->
            <h4 class="text-md font-bold text-gray-700 mt-8 mb-4 border-b pb-2">المتابعة والحالة</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="info-item">
                    <label>حالة العميل</label>
                    <p>{{ $client->client_status_label }}</p>
                </div>
                <div class="info-item">
                    <label>موعد المتابعة</label>
                    <p>{{ $client->next_follow_up_date?->format('d/m/Y') ?? '-' }}</p>
                </div>
            </div>

            <!-- Section Title -->
            <h4 class="text-md font-bold text-gray-700 mt-8 mb-4 border-b pb-2">الوثائق والمستندات</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                 <div class="info-item">
                    <label>الوثيقة المؤقتة</label>
                    <p>{{ $client->temporary_document ?? '-' }}</p>
                </div>
                 <div class="info-item">
                    <label>اسم الشيخ</label>
                    <p>{{ $client->sheikh_name ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>رقم الدفتر</label>
                    <p>{{ $client->book_number ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>رقم الوثيقة</label>
                    <p>{{ $client->document_number ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>منفذ العقد</label>
                    <p>{{ $client->contract_executor ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>تاريخ وصول القسيمة</label>
                    <p>{{ $client->coupon_arrival_date?->format('d/m/Y') ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>تاريخ استلام الوثيقة</label>
                    <p>{{ $client->document_receipt_date?->format('d/m/Y') ?? '-' }}</p>
                </div>
                 <div class="info-item">
                    <label>مستلم الوثيقة</label>
                    <p>{{ $client->document_receiver_label }}</p>
                </div>
                 <div class="info-item">
                    <label>اسم الدليفري</label>
                    <p>{{ $client->delivery_man_name ?? '-' }}</p>
                </div>
                 <div class="info-item">
                    <label>اسم قريب العميل</label>
                    <p>{{ $client->client_relative_name ?? '-' }}</p>
                </div>
                <div class="info-item">
                    <label>اسم العميل المستلم</label>
                    <p>{{ $client->client_receiver_name ?? '-' }}</p>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="mt-8">
                <h4 class="text-md font-bold text-gray-700 mb-2 border-b pb-2">ملاحظات</h4>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-gray-700 whitespace-pre-wrap">{{ $client->notes ?? 'لا توجد ملاحظات.' }}</p>
                </div>
            </div>

            <!-- Documents List -->
            <div class="mt-8">
                <h4 class="text-md font-bold text-gray-700 mb-2 border-b pb-2">المستندات المرفقة ({{ $client->media->count() }})</h4>
                @if($client->media->count() > 0)
                    <ul class="space-y-3">
                        @foreach($client->media as $document)
                            <li class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-gray-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <span class="font-medium">{{ $document->file_name }}</span>
                                    <span class="text-sm text-gray-500 mr-2">({{ number_format($document->size / 1024, 2) }} KB)</span>
                                </div>
                                <div class="space-x-2 space-x-reverse">
                                    <a href="{{ $document->getUrl() }}" target="_blank" class="text-blue-600 hover:underline">عرض</a>
                                    <a href="{{ $document->getUrl() }}" download class="text-green-600 hover:underline">تحميل</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-500">لم يتم إرفاق أي مستندات.</p>
                @endif
            </div>

        </div>
    </div>
</div>
<style>
    .info-item {
        @apply flex flex-col;
    }
    .info-item label {
        @apply text-sm font-medium text-gray-500 mb-1;
    }
    .info-item p {
        @apply text-base text-gray-800 font-semibold;
    }
</style>
@endsection

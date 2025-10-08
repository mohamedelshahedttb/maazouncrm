<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طباعة بيانات العميل - {{ $client->name }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; /* Using a font that supports Arabic characters */
            color: #111827; 
            line-height: 1.6;
        }
        .container { 
            max-width: 900px; 
            margin: 0 auto; 
            padding: 24px; 
        }
        .header {
            text-align: center;
            margin-bottom: 24px;
        }
        .title { 
            font-size: 24px; 
            font-weight: 800; 
            margin-bottom: 8px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .section { 
            margin-bottom: 24px; 
        }
        .section-title { 
            font-size: 18px; 
            font-weight: 700; 
            margin: 16px 0 12px;
            color: #1f2937;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 6px;
        }
        .grid { 
            display: grid; 
            grid-template-columns: 1fr 1fr 1fr; 
            gap: 16px 24px; 
        }
        .grid-item {
            background-color: #f9fafb;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #f3f4f6;
        }
        .label { 
            font-size: 13px; 
            color: #6b7280; 
            display: block;
            margin-bottom: 4px;
        }
        .value { 
            font-size: 14px; 
            font-weight: 600; 
            color: #111827; 
        }
        .full-width {
            grid-column: 1 / -1;
        }
        .hr { 
            height: 1px; 
            background: #e5e7eb; 
            margin: 24px 0; 
            border: 0; 
        }
        .muted { color: #6b7280; }
        .document-list { list-style: none; padding: 0; }
        .document-list li { padding: 4px 0; border-bottom: 1px dashed #e5e7eb; }
        @media print { 
            .no-print { display: none; } 
        }
    </style>
    <script>function doPrint(){ window.print(); }</script>
</head>
<body>
    <div class="container">
        <div class="no-print" style="text-align:left; margin-bottom: 12px;">
            <button onclick="doPrint()" style="padding:8px 16px; border:1px solid #374151; border-radius:6px; background:#1f2937; color:#fff; cursor:pointer; font-size: 14px;">طباعة</button>
        </div>

        <div class="header">
            <div class="title">بيانات العميل: {{ $client->name }}</div>
        </div>

        <div class="section">
            <div class="section-title">المعلومات الأساسية</div>
            <div class="grid">
                <div class="grid-item"><span class="label">اسم العميل</span><span class="value">{{ $client->name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">مصدر العميل</span><span class="value">{{ $client->source->name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">تاريخ العقد</span><span class="value">{{ $client->event_date?->format('d/m/Y') ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">اسم الزوج</span><span class="value">{{ $client->groom_name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">اسم الزوجة</span><span class="value">{{ $client->bride_name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">سن الزوجة</span><span class="value">{{ $client->bride_age ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">صلة قرابة الولي</span><span class="value">{{ $client->relationship_status ?? '-' }}</span></div>
                <div class="grid-item full-width"><span class="label">محل إقامة الزوجة</span><span class="value">{{ $client->bride_id_address ?? '-' }}</span></div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">معلومات التواصل والموقع</div>
            <div class="grid">
                <div class="grid-item"><span class="label">رقم الهاتف</span><span class="value">{{ $client->phone ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">رقم الواتساب</span><span class="value">{{ $client->whatsapp_number ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">مكان العقد</span><span class="value">{{ $client->contract_location ?? '-' }}</span></div>
                <div class="grid-item full-width"><span class="label">عنوان العقد بالتفصيل</span><span class="value">{{ $client->contract_address ?? '-' }}</span></div>
                <div class="grid-item full-width"><span class="label">رابط الموقع (خرائط جوجل)</span><span class="value">{{ $client->google_maps_link ?? '-' }}</span></div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">التسعير والخدمات</div>
            <div class="grid">
                <div class="grid-item"><span class="label">الخدمة المطلوبة</span><span class="value">{{ $client->service->name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">المحافظة (إن وجدت)</span><span class="value">{{ $client->governorate->name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">المنطقة</span><span class="value">{{ $client->area->name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">مبلغ المؤخر</span><span class="value">{{ number_format((float)($client->mahr ?? 0), 2) }}</span></div>
                <div class="grid-item"><span class="label">الخصم</span><span class="value">@if($client->discount_value){{ $client->discount_value }} {{ $client->discount_type == 'percentage' ? '%' : 'جنيه' }}@else - @endif</span></div>
                <div class="grid-item"><span class="label">السعر النهائي</span><span class="value">{{ number_format($client->final_price, 2) ?? '0.00' }} جنيه</span></div>
                <div class="grid-item full-width"><span class="label">اكسسوارات العقد</span><span class="value">@forelse($client->accessories ?? [] as $productId) @php $product = \App\Models\Product::find($productId); @endphp @if($product){{ $product->name }}@if(!$loop->last), @endif @endif @empty - @endforelse</span></div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">المتابعة والحالة</div>
            <div class="grid">
                <div class="grid-item"><span class="label">حالة العميل</span><span class="value">{{ $client->client_status_label }}</span></div>
                <div class="grid-item"><span class="label">موعد المتابعة</span><span class="value">{{ $client->next_follow_up_date?->format('d/m/Y') ?? '-' }}</span></div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">الوثائق والمستندات</div>
            <div class="grid">
                <div class="grid-item"><span class="label">الوثيقة المؤقتة</span><span class="value">{{ $client->temporary_document ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">اسم الشيخ</span><span class="value">{{ $client->sheikh_name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">رقم الدفتر</span><span class="value">{{ $client->book_number ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">رقم الوثيقة</span><span class="value">{{ $client->document_number ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">تاريخ وصول القسيمة</span><span class="value">{{ $client->coupon_arrival_date?->format('d/m/Y') ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">تاريخ استلام الوثيقة</span><span class="value">{{ $client->document_receipt_date?->format('d/m/Y') ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">مستلم الوثيقة</span><span class="value">{{ $client->document_receiver_label }}</span></div>
                <div class="grid-item"><span class="label">اسم الدليفري</span><span class="value">{{ $client->delivery_man_name ?? '-' }}</span></div>
                <div class="grid-item"><span class="label">اسم قريب العميل</span><span class="value">{{ $client->client_relative_name ?? '-' }}</span></div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">ملاحظات</div>
            <div class="grid-item full-width">
                <span class="value">{{ $client->notes ?? 'لا توجد ملاحظات.' }}</span>
            </div>
        </div>

        @if($client->media->count())
            <hr class="hr">
            <div class="section">
                <div class="section-title">المستندات المرفقة ({{ $client->media->count() }})</div>
                <ul class="document-list muted">
                    @foreach($client->media as $m)
                        <li>{{ $m->file_name }} ({{ $m->mime_type }})</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </div>
</body>
</html>



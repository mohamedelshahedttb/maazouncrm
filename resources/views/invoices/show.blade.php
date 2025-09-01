@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">تفاصيل الفاتورة</h1>
                <p class="text-gray-600 mt-2">رقم الفاتورة: {{ $invoice->invoice_number }}</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('invoices.edit', $invoice) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    تعديل الفاتورة
                </a>
                <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <!-- Invoice Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Invoice Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات الفاتورة</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-600">رقم الفاتورة:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->invoice_number }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">تاريخ الإصدار:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->issue_date->format('Y-m-d') }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">تاريخ الاستحقاق:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->due_date->format('Y-m-d') }}</span>
                        </div>
                        @if($invoice->paid_date)
                        <div>
                            <span class="text-sm font-medium text-gray-600">تاريخ الدفع:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->paid_date->format('Y-m-d') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Client Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات العميل</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-600">اسم العميل:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->client->name }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">رقم الهاتف:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->client->phone }}</span>
                        </div>
                        @if($invoice->client->email)
                        <div>
                            <span class="text-sm font-medium text-gray-600">البريد الإلكتروني:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->client->email }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Service Info -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات الخدمة</h3>
                    <div class="space-y-3">
                        <div>
                            <span class="text-sm font-medium text-gray-600">اسم الخدمة:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->service->name }}</span>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">سعر الخدمة:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->service->price }} {{ $invoice->service->currency }}</span>
                        </div>
                        @if($invoice->appointment)
                        <div>
                            <span class="text-sm font-medium text-gray-600">الموعد المرتبط:</span>
                            <span class="text-sm text-gray-800 mr-2">{{ $invoice->appointment->appointment_date->format('Y-m-d H:i') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Details -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">تفاصيل الفاتورة</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600">الوصف</th>
                            <th class="text-right py-3 px-4 text-sm font-medium text-gray-600">المبلغ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-sm text-gray-800">المبلغ الفرعي</td>
                            <td class="py-3 px-4 text-sm text-gray-800 text-left">{{ number_format($invoice->subtotal, 2) }} {{ $invoice->currency }}</td>
                        </tr>
                        @if($invoice->tax_amount > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-sm text-gray-800">مبلغ الضريبة</td>
                            <td class="py-3 px-4 text-sm text-gray-800 text-left">{{ number_format($invoice->tax_amount, 2) }} {{ $invoice->currency }}</td>
                        </tr>
                        @endif
                        @if($invoice->discount_amount > 0)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-4 text-sm text-gray-800">مبلغ الخصم</td>
                            <td class="py-3 px-4 text-sm text-gray-800 text-left">-{{ number_format($invoice->discount_amount, 2) }} {{ $invoice->currency }}</td>
                        </tr>
                        @endif
                        <tr class="bg-blue-50">
                            <td class="py-3 px-4 text-sm font-semibold text-gray-800">المبلغ الإجمالي</td>
                            <td class="py-3 px-4 text-sm font-semibold text-blue-800 text-left">{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Status and Additional Info -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">حالة الفاتورة</h3>
                <div class="flex items-center">
                    @php
                        $statusColors = [
                            'draft' => 'bg-gray-100 text-gray-800',
                            'sent' => 'bg-blue-100 text-blue-800',
                            'paid' => 'bg-green-100 text-green-800',
                            'overdue' => 'bg-red-100 text-red-800',
                            'cancelled' => 'bg-red-100 text-red-800'
                        ];
                        $statusLabels = [
                            'draft' => 'مسودة',
                            'sent' => 'مرسلة',
                            'paid' => 'مدفوعة',
                            'overdue' => 'متأخرة',
                            'cancelled' => 'ملغية'
                        ];
                    @endphp
                    <span class="px-3 py-2 rounded-full text-sm font-medium {{ $statusColors[$invoice->status] ?? 'bg-gray-100 text-gray-800' }}">
                        {{ $statusLabels[$invoice->status] ?? $invoice->status }}
                    </span>
                </div>
                
                @if($invoice->status === 'paid')
                <div class="mt-4">
                    <span class="text-sm font-medium text-gray-600">تم الدفع في:</span>
                    <span class="text-sm text-gray-800 mr-2">{{ $invoice->paid_date->format('Y-m-d') }}</span>
                </div>
                @endif
            </div>

            <!-- Payment Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات الدفع</h3>
                <div class="space-y-3">
                    @if($invoice->payment_method)
                    <div>
                        <span class="text-sm font-medium text-gray-600">طريقة الدفع:</span>
                        <span class="text-sm text-gray-800 mr-2">{{ $invoice->payment_method }}</span>
                    </div>
                    @endif
                    @if($invoice->payment_terms)
                    <div>
                        <span class="text-sm font-medium text-gray-600">شروط الدفع:</span>
                        <span class="text-sm text-gray-800 mr-2">{{ $invoice->payment_terms }}</span>
                    </div>
                    @endif
                    @if($invoice->billing_address)
                    <div>
                        <span class="text-sm font-medium text-gray-600">عنوان الفواتير:</span>
                        <span class="text-sm text-gray-800 mr-2">{{ $invoice->billing_address }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">ملاحظات</h3>
            <p class="text-gray-700">{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Actions -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">الإجراءات</h3>
            <div class="flex flex-wrap gap-3">
                @if($invoice->status === 'draft')
                <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        إرسال الفاتورة
                    </button>
                </form>
                @endif
                
                @if($invoice->status === 'sent' && $invoice->status !== 'paid')
                <form action="{{ route('invoices.mark-as-paid', $invoice) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        تم الدفع
                    </button>
                </form>
                @endif
                
                @if($invoice->status !== 'cancelled' && $invoice->status !== 'paid')
                <form action="{{ route('invoices.cancel', $invoice) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium">
                        إلغاء الفاتورة
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

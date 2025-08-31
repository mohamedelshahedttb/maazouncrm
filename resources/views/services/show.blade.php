@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $service->name }}</h1>
                <p class="text-gray-600 mt-2">{{ $service->category_label }}</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('services.edit', $service) }}" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تعديل الخدمة
                </a>
                <a href="{{ route('services.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Basic Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">المعلومات الأساسية</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600">اسم الخدمة</label>
                            <p class="mt-1 text-gray-900">{{ $service->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">الفئة</label>
                            <p class="mt-1 text-gray-900">{{ $service->category_label }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">السعر</label>
                            <p class="mt-1 text-gray-900">{{ number_format($service->price, 2) }} {{ $service->currency }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">المدة</label>
                            <p class="mt-1 text-gray-900">
                                @if($service->duration_minutes)
                                    {{ $service->formatted_total_duration }}
                                @else
                                    غير محدد
                                @endif
                            </p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">الحالة</label>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $service->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">معلومات إضافية</h2>
                    <div class="space-y-4">
                        @if($service->description)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">وصف الخدمة</label>
                            <p class="mt-1 text-gray-900">{{ $service->description }}</p>
                        </div>
                        @endif
                        
                        @if($service->requirements)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">المتطلبات</label>
                            <p class="mt-1 text-gray-900">{{ $service->requirements }}</p>
                        </div>
                        @endif
                        
                        @if($service->notes)
                        <div>
                            <label class="block text-sm font-medium text-gray-600">ملاحظات</label>
                            <p class="mt-1 text-gray-900">{{ $service->notes }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">تاريخ الإنشاء</label>
                            <p class="mt-1 text-gray-900">{{ $service->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-600">آخر تحديث</label>
                            <p class="mt-1 text-gray-900">{{ $service->updated_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            @if($service->hasExecutionSteps)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">خطوات التنفيذ</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-600">هذه الخدمة تحتوي على {{ $service->execution_steps_count }} خطوة تنفيذ</p>
                    <div class="mt-3 flex space-x-2 space-x-reverse">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            خطوات الإعداد: {{ $service->preparationSteps->count() }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            خطوات التنفيذ: {{ $service->executionSteps->count() }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            خطوات التحقق: {{ $service->verificationSteps->count() }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                            خطوات التسليم: {{ $service->deliverySteps->count() }}
                        </span>
                    </div>
                </div>
            </div>
            @endif

            @if($service->invoices->count() > 0)
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">الفواتير المرتبطة</h2>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-600">إجمالي الفواتير: {{ $service->invoices->count() }}</p>
                    <p class="text-gray-600">إجمالي الإيرادات: {{ number_format($service->invoices->sum('total_amount'), 2) }} {{ $service->currency }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


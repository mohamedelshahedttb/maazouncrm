@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">تفاصيل العميل</h1>
            <p class="text-gray-600 mt-2">{{ $client->name }}</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('clients.edit', $client) }}" class="bg-blue-500 hover:bg-blue-600 text-blue-100 px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل العميل
            </a>
            <a href="{{ route('clients.index') }}" class="bg-gray-500 hover:bg-gray-600 text-blue-100 px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                عودة للقائمة
            </a>
        </div>
    </div>
            <!-- Client Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات العميل الأساسية</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class="text-sm font-medium text-gray-500">اسم العميل</p>
                            <p class="text-lg text-gray-900">{{ $client->name }}</p>
                        </div>
                        
                        @if($client->bride_name)
                        <div>
                            <p class="text-sm font-medium text-gray-500">اسم العروس</p>
                            <p class="text-lg text-gray-900">{{ $client->bride_name }}</p>
                        </div>
                        @endif
                        
                        @if($client->guardian_name)
                        <div>
                            <p class="text-sm font-medium text-gray-500">اسم ولي الأمر</p>
                            <p class="text-lg text-gray-900">{{ $client->guardian_name }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">رقم الهاتف</p>
                            <p class="text-lg text-gray-900">{{ $client->phone }}</p>
                        </div>
                        
                        @if($client->email)
                        <div>
                            <p class="text-sm font-medium text-gray-500">البريد الإلكتروني</p>
                            <p class="text-lg text-gray-900">{{ $client->email }}</p>
                        </div>
                        @endif
                        
                        @if($client->whatsapp_number)
                        <div>
                            <p class="text-sm font-medium text-gray-500">رقم الواتساب</p>
                            <p class="text-lg text-gray-900">{{ $client->whatsapp_number }}</p>
                        </div>
                        @endif
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">حالة العميل</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($client->status === 'new') bg-blue-100 text-blue-800
                                @elseif($client->status === 'in_progress') bg-yellow-100 text-yellow-800
                                @elseif($client->status === 'completed') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $client->status_label }}
                            </span>
                        </div>
                        
                        <div>
                            <p class="text-sm font-medium text-gray-500">حالة النشاط</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if($client->is_active) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ $client->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($client->address)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-500">العنوان</p>
                        <p class="text-lg text-gray-900">{{ $client->address }}</p>
                    </div>
                    @endif
                    
                    @if($client->notes)
                    <div class="mt-6">
                        <p class="text-sm font-medium text-gray-500">الملاحظات</p>
                        <p class="text-lg text-gray-900 whitespace-pre-line">{{ $client->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Appointments -->
            @if($recentAppointments->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">المواعيد الأخيرة</h3>
                    <div class="space-y-3">
                        @foreach($recentAppointments as $appointment)
                        <div class="border-l-4 border-blue-400 pl-4 py-2">
                            <p class="font-medium">{{ $appointment->service->name ?? 'خدمة غير محددة' }}</p>
                            <p class="text-sm text-gray-600">{{ $appointment->appointment_date->format('Y-m-d H:i') }}</p>
                            <p class="text-sm text-gray-500">{{ $appointment->status_label ?? $appointment->status }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Orders -->
            @if($recentOrders->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">الطلبات الأخيرة</h3>
                    <div class="space-y-3">
                        @foreach($recentOrders as $order)
                        <div class="border-l-4 border-green-400 pl-4 py-2">
                            <p class="font-medium">{{ $order->service->name ?? 'خدمة غير محددة' }}</p>
                            <p class="text-sm text-gray-600">{{ $order->total_amount }} {{ $order->currency }}</p>
                            <p class="text-sm text-gray-500">{{ $order->status_label ?? $order->status }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Documents -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">المستندات</h3>
                        <button onclick="document.getElementById('documentUpload').click()" class="bg-blue-500 hover:bg-blue-600 text-blue-100 px-4 py-2 rounded-lg text-sm font-medium flex items-center">
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            إضافة مستند
                        </button>
                    </div>
                    
                    @if($client->media->count() > 0)
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($client->media as $media)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    @if(in_array($media->mime_type, ['image/jpeg', 'image/png', 'image/jpg']))
                                        <svg class="w-5 h-5 text-blue-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-red-500 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @endif
                                    <span class="text-sm font-medium text-gray-900">{{ $media->name }}</span>
                                </div>
                                <div class="flex space-x-1 space-x-reverse">
                                    <a href="{{ $client->getCorrectMediaUrl($media) }}" target="_blank" class="text-blue-600 hover:text-blue-800 p-1" title="عرض">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </a>
                                    <a href="{{ $client->getCorrectMediaUrl($media) }}" download class="text-green-600 hover:text-green-800 p-1" title="تحميل">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                    <form action="{{ route('clients.documents.destroy', ['client' => $client, 'document' => $media->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 p-1" title="حذف" onclick="return confirm('هل أنت متأكد من حذف هذا المستند؟')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500">{{ $media->mime_type }} - {{ $media->size }} bytes</p>
                            <p class="text-xs text-gray-400">{{ $media->created_at->format('Y-m-d H:i') }}</p>
                            <!-- Debug information -->
                            <div class="mt-2 p-2 bg-gray-100 rounded text-xs">
                                <p><strong>Debug:</strong></p>
                                <p>Original URL: {{ $media->getUrl() }}</p>
                                <p>Custom View URL: {{ $client->getCorrectMediaUrl($media) }}</p>
                                <p>Custom Download URL: {{ $client->getCorrectMediaDownloadUrl($media) }}</p>
                                <p>Path: {{ $media->getPath() }}</p>
                                <p>Disk: {{ $media->disk }}</p>
                                <p>Collection: {{ $media->collection_name }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-500">لا توجد مستندات مرفقة</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Hidden file input for document upload -->
            <form id="documentUploadForm" action="{{ route('clients.documents.store', $client) }}" method="POST" enctype="multipart/form-data" class="hidden">
                @csrf
                <input type="file" id="documentUpload" name="documents[]" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" onchange="this.form.submit()">
            </form>

            <!-- Required Documents -->
            @if($client->service && $client->service->activeRequiredDocuments->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">المستندات المطلوبة للخدمة</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($client->service->activeRequiredDocuments as $requiredDoc)
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 rounded-full ml-2 
                                        @if($requiredDoc->isRequired()) bg-red-500
                                        @elseif($requiredDoc->isOptional()) bg-blue-500
                                        @else bg-yellow-500
                                        @endif">
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $requiredDoc->document_name }}</span>
                                </div>
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if($requiredDoc->isRequired()) bg-red-100 text-red-800
                                    @elseif($requiredDoc->isOptional()) bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800
                                    @endif">
                                    {{ $requiredDoc->document_type_label }}
                                </span>
                            </div>
                            
                            @if($requiredDoc->description)
                            <p class="text-sm text-gray-600 mb-2">{{ $requiredDoc->description }}</p>
                            @endif
                            
                            <div class="text-xs text-gray-500 space-y-1">
                                <div>الصيغة: {{ $requiredDoc->formatted_file_format }}</div>
                                <div>الحد الأقصى: {{ $requiredDoc->formatted_max_file_size }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Quick Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">إجراءات سريعة</h3>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('clients.appointments.create', $client) }}" class="bg-purple-600 hover:bg-purple-700 text-blue-100 font-bold py-2 px-4 rounded">
                            إنشاء موعد
                        </a>
                        <a href="{{ route('clients.conversations', $client) }}" class="bg-green-600 hover:bg-green-700 text-blue-100 font-bold py-2 px-4 rounded">
                            إضافة محادثة
                        </a>
                        <a href="{{ route('clients.orders', $client) }}" class="bg-blue-600 hover:bg-blue-700 text-blue-100 font-bold py-2 px-4 rounded">
                            عرض الطلبات
                        </a>
                        <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="bg-green-600 hover:bg-green-700 text-blue-100 font-bold py-2 px-4 rounded">
                            إنشاء فاتورة
                        </a>
                        <button class="bg-yellow-600 hover:bg-yellow-700 text-blue-100 font-bold py-2 px-4 rounded">
                            إرسال تذكير واتساب
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

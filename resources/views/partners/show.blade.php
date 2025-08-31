@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title and Actions -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">تفاصيل الشريك</h1>
            <p class="text-gray-600 mt-2">عرض معلومات الشريك والتفاصيل</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('partners.edit', $partner) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل
            </a>
            <a href="{{ route('partners.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                عودة
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">المعلومات الأساسية</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">اسم الشريك</label>
                        <p class="text-lg font-medium text-gray-900">{{ $partner->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">رقم الترخيص</label>
                        <p class="text-lg font-medium text-gray-900">{{ $partner->license_number }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">رقم الهاتف</label>
                        <p class="text-lg font-medium text-gray-900">{{ $partner->phone }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">البريد الإلكتروني</label>
                        <p class="text-lg font-medium text-gray-900">{{ $partner->email ?? 'غير محدد' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">نطاق الخدمات</label>
                        <p class="text-lg font-medium text-gray-900">{{ $partner->service_scope }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">نسبة العمولة</label>
                        <p class="text-lg font-medium text-gray-900">{{ $partner->commission_rate }}%</p>
                    </div>
                </div>
            </div>

            <!-- Address -->
            @if($partner->address)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">العنوان</h3>
                <p class="text-gray-700">{{ $partner->address }}</p>
            </div>
            @endif

            <!-- Notes -->
            @if($partner->notes)
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">ملاحظات</h3>
                <p class="text-gray-700">{{ $partner->notes }}</p>
            </div>
            @endif

            <!-- Recent Assistance Requests -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">طلبات المساعدة الحديثة</h3>
                @if($partner->assistanceRequests && $partner->assistanceRequests->count() > 0)
                    <div class="space-y-3">
                        @foreach($partner->assistanceRequests->take(5) as $request)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-medium text-gray-900">طلب #{{ $request->id }}</p>
                                <p class="text-sm text-gray-600">{{ $request->created_at->format('Y-m-d') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-medium text-gray-900">{{ $request->service_type }}</p>
                                <p class="text-sm text-gray-600">{{ $request->status }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('partner-assistance-requests.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                            عرض جميع الطلبات →
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 text-center py-4">لا توجد طلبات مساعدة حديثة</p>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">حالة الشريك</h3>
                <div class="text-center">
                    @if($partner->is_active)
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-green-800">نشط</p>
                        <p class="text-sm text-gray-600">الشريك متاح للعمل</p>
                    @else
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <p class="text-lg font-medium text-red-800">غير نشط</p>
                        <p class="text-sm text-gray-600">الشريك غير متاح</p>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">إجراءات سريعة</h3>
                <div class="space-y-3">
                    <a href="{{ route('partner-assistance-requests.create') }}" class="w-full flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        طلب مساعدة جديد
                    </a>
                    <a href="{{ route('partners.edit', $partner) }}" class="w-full flex items-center justify-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        تعديل
                    </a>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات الاتصال</h3>
                <div class="space-y-3">
                    @if($partner->phone)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $partner->phone }}</span>
                    </div>
                    @endif
                    @if($partner->email)
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-gray-700">{{ $partner->email }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Commission Info -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">معلومات العمولة</h3>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ $partner->commission_rate }}%</div>
                    <p class="text-sm text-gray-600">نسبة العمولة المتفق عليها</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

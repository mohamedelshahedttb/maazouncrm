@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">التقارير</h1>
        <p class="text-gray-600 mt-2">عرض وتحليل بيانات النظام</p>
    </div>

    <!-- Reports Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Dashboard Overview Report -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">نظرة عامة</h3>
                    <p class="text-sm text-gray-600">ملخص شامل للنظام</p>
                </div>
            </div>
            <a href="{{ route('reports.index') }}" class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                عرض التقرير
            </a>
        </div>

        <!-- Products Report -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">تقرير المنتجات</h3>
                    <p class="text-sm text-gray-600">تحليل المخزون والمبيعات</p>
                </div>
            </div>
            <a href="{{ route('reports.products') }}" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                عرض التقرير
            </a>
        </div>

        <!-- Operations Report -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">تقرير العمليات</h3>
                    <p class="text-sm text-gray-600">تحليل المهام والخدمات</p>
                </div>
            </div>
            <a href="{{ route('reports.operations') }}" class="w-full bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                عرض التقرير
            </a>
        </div>

        <!-- Performance Report -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">تقرير الأداء</h3>
                    <p class="text-sm text-gray-600">تحليل الإنتاجية والكفاءة</p>
                </div>
            </div>
            <a href="{{ route('reports.performance') }}" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                عرض التقرير
            </a>
        </div>

        <!-- Revenue Report -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">تقرير الإيرادات</h3>
                    <p class="text-sm text-gray-600">تحليل الأرباح والخسائر</p>
                </div>
            </div>
            <a href="{{ route('reports.revenue') }}" class="w-full bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                عرض التقرير
            </a>
        </div>

        <!-- Client Report -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center mb-4">
                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">تقرير العملاء</h3>
                    <p class="text-sm text-gray-600">تحليل العملاء والخدمات</p>
                </div>
            </div>
            <a href="{{ route('reports.clients') }}" class="w-full bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg font-medium text-center block">
                عرض التقرير
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">إحصائيات سريعة</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">{{ \App\Models\Client::count() }}</div>
                <div class="text-sm text-gray-600">إجمالي العملاء</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">{{ \App\Models\Appointment::whereDate('appointment_date', today())->count() }}</div>
                <div class="text-sm text-gray-600">مواعيد اليوم</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-600 mb-2">{{ \App\Models\Task::where('status', 'pending')->count() }}</div>
                <div class="text-sm text-gray-600">مهام معلقة</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">{{ \App\Models\Product::where('stock_quantity', '<=', 'low_stock_threshold')->count() }}</div>
                <div class="text-sm text-gray-600">منتجات منخفضة المخزون</div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">النشاطات الحديثة</h3>
        <div class="space-y-4">
            @forelse(\App\Models\Client::latest()->take(5)->get() as $client)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                        <span class="text-blue-600 font-semibold text-xs">{{ strtoupper(substr($client->name, 0, 1)) }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">تم إضافة عميل جديد: {{ $client->name }}</p>
                        <p class="text-sm text-gray-600">{{ $client->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    عرض →
                </a>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <p>لا توجد نشاطات حديثة</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection

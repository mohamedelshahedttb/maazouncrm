@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">تقرير الإيرادات</h1>
        <p class="text-gray-600 mt-2">نظرة عامة على الإيرادات والأرباح</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الإيرادات الشهرية</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyRevenue ?? 0 }} جنيه مصري</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">الطلبات الشهرية</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $monthlyOrders ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <div class="mr-4">
                    <p class="text-sm font-medium text-gray-600">متوسط الطلب</p>
                    <p class="text-2xl font-bold text-gray-900">
                        {{ ($monthlyOrders > 0 && $monthlyRevenue > 0) ? round($monthlyRevenue / $monthlyOrders, 2) : 0 }} جنيه مصري
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">ملخص الإيرادات</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3">الإحصائيات الشهرية</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">إجمالي الإيرادات</span>
                        <span class="font-semibold text-gray-900">{{ $monthlyRevenue ?? 0 }} جنيه مصري</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">عدد الطلبات</span>
                        <span class="font-semibold text-gray-900">{{ $monthlyOrders ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">متوسط الطلب</span>
                        <span class="font-semibold text-gray-900">
                            {{ ($monthlyOrders > 0 && $monthlyRevenue > 0) ? round($monthlyRevenue / $monthlyOrders, 2) : 0 }} جنيه مصري
                        </span>
                    </div>
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-medium text-gray-800 mb-3">مقارنة مع الشهر السابق</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">نسبة التغيير</span>
                        <span class="font-semibold text-green-600">+15%</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">حالة الأداء</span>
                        <span class="px-2 py-1 text-xs font-medium bg-green-100 text-green-800 rounded-full">ممتاز</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

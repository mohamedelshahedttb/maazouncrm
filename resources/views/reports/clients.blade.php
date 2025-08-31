@extends('layouts.app')

@section('title', 'تقرير العملاء')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">تقرير العملاء</h1>
            <a href="{{ route('reports.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                العودة للتقارير
            </a>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">إجمالي العملاء</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $totalClients }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">العملاء النشطون</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $activeClients }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">العملاء المحتملون</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $potentialClients }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">العملاء المكتملون</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $completedClients }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Comparison -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">مقارنة شهري</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">هذا الشهر</span>
                        <span class="text-lg font-semibold text-blue-600">{{ $monthlyNewClients }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">الشهر الماضي</span>
                        <span class="text-lg font-semibold text-gray-600">{{ $previousMonthClients }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">النسبة المئوية</span>
                        <span class="text-lg font-semibold {{ $previousMonthClients > 0 ? ($monthlyNewClients - $previousMonthClients) / $previousMonthClients * 100 > 0 ? 'text-green-600' : 'text-red-600' : 'text-gray-600' }}">
                            @if($previousMonthClients > 0)
                                {{ number_format(($monthlyNewClients - $previousMonthClients) / $previousMonthClients * 100, 1) }}%
                            @else
                                -
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">أفضل الخدمات</h3>
                <div class="space-y-3">
                    @forelse($topServices as $service)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">{{ $service->name }}</span>
                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-sm font-medium">
                                {{ $service->clients_count }} عميل
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">لا توجد خدمات متاحة</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">العملاء حسب المصدر</h3>
                <div class="space-y-3">
                    @forelse($clientsBySource as $sourceData)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">
                                {{ $sourceData->source ? $sourceData->source->name : 'غير محدد' }}
                            </span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm font-medium">
                                {{ $sourceData->count }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">لا توجد بيانات متاحة</p>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">العملاء حسب الخدمة</h3>
                <div class="space-y-3">
                    @forelse($clientsByService as $serviceData)
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">
                                {{ $serviceData->service ? $serviceData->service->name : 'غير محدد' }}
                            </span>
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm font-medium">
                                {{ $serviceData->count }}
                            </span>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">لا توجد بيانات متاحة</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Clients -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">أحدث العملاء</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الخدمة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المصدر</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($recentClients as $client)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $client->phone }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">
                                        {{ $client->service ? $client->service->name : 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm text-gray-900">
                                        {{ $client->source ? $client->source->name : 'غير محدد' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'active' => 'bg-green-100 text-green-800',
                                            'potential' => 'bg-yellow-100 text-yellow-800',
                                            'completed' => 'bg-blue-100 text-blue-800',
                                            'inactive' => 'bg-red-100 text-red-800'
                                        ];
                                        $statusColor = $statusColors[$client->status] ?? 'bg-gray-100 text-gray-800';
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColor }}">
                                        {{ $client->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $client->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">لا توجد عملاء متاحون</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

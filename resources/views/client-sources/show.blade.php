@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">تفاصيل مصدر العميل</h1>
                <p class="text-gray-600 mt-2">{{ $clientSource->name }}</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('client-sources.edit', $clientSource) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تعديل
                </a>
                <a href="{{ route('client-sources.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">اسم المصدر</label>
                    <p class="text-gray-900">{{ $clientSource->name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">نوع المصدر</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ $clientSource->type_label }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $clientSource->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $clientSource->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">تاريخ الإنشاء</label>
                    <p class="text-gray-900">{{ $clientSource->created_at->format('Y-m-d H:i') }}</p>
                </div>
                
                @if($clientSource->description)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-500 mb-2">الوصف</label>
                    <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $clientSource->description }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Clients using this source -->
        <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">العملاء الذين يستخدمون هذا المصدر</h3>
                
                @if($clientSource->clients->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">اسم العميل</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الهاتف</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">تاريخ الإنشاء</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($clientSource->clients as $client)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $client->phone }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $client->status_color }}-100 text-{{ $client->status_color }}-800">
                                        {{ $client->status_label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $client->created_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('clients.show', $client) }}" class="text-blue-600 hover:text-blue-900">عرض</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <p class="text-gray-500 text-center py-8">لا يوجد عملاء يستخدمون هذا المصدر حالياً</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection



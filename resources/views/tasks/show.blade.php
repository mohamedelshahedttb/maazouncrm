@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $task->title }}</h1>
                <p class="text-gray-600 mt-2">تفاصيل المهمة</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('tasks.edit', $task) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-blue rounded-lg font-medium">
                    تعديل
                </a>
                <a href="{{ route('tasks.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">معلومات المهمة</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">العنوان</label>
                            <p class="text-gray-900">{{ $task->title }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $task->status === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $task->status === 'in_progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $task->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $task->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $task->status === 'delayed' ? 'bg-orange-100 text-orange-800' : '' }}">
                                {{ $task->status }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الأولوية</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                {{ $task->priority === 'urgent' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $task->priority === 'high' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $task->priority === 'medium' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $task->priority === 'low' ? 'bg-green-100 text-green-800' : '' }}">
                                {{ $task->priority }}
                            </span>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">تاريخ الاستحقاق</label>
                            <p class="text-gray-900">{{ $task->due_date ? $task->due_date->format('Y-m-d H:i') : 'غير محدد' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الموقع</label>
                            <p class="text-gray-900">{{ $task->location ?: 'غير محدد' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $task->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $task->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($task->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">الوصف</label>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $task->description }}</p>
                    </div>
                    @endif
                    
                    @if($task->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">الملاحظات</label>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $task->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">إحصائيات المهمة</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">تاريخ الإنشاء</span>
                            <span class="text-gray-900 font-medium">{{ $task->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">آخر تحديث</span>
                            <span class="text-gray-900 font-medium">{{ $task->updated_at->format('Y-m-d') }}</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">إجراءات سريعة</h3>
                    <div class="space-y-3">
                        @if($task->status !== 'completed')
                        <form action="{{ route('tasks.update', $task) }}" method="POST" class="inline-block w-full">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="completed">
                            <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-blue rounded-lg font-medium">
                                إكمال المهمة
                            </button>
                        </form>
                        @endif
                        
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="inline-block w-full" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذه المهمة؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-blue rounded-lg font-medium">
                                حذف المهمة
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

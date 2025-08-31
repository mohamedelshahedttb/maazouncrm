@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-blue-800">تفاصيل المستخدم</h1>
            <p class="text-gray-600 mt-2">{{ $user->name }}</p>
        </div>
        <div class="flex space-x-3 space-x-reverse">
            <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-blue px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                تعديل المستخدم
            </a>
            <a href="{{ route('users.index') }}" class="bg-gray-500 hover:bg-blue-600 text-blue px-6 py-3 rounded-lg font-medium flex items-center">
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                عودة للقائمة
            </a>
        </div>
    </div>

    <!-- User Information -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6">
            <h3 class="text-lg font-semibold text-blue-800 mb-4">معلومات المستخدم الأساسية</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500">الاسم الكامل</p>
                    <p class="text-lg text-gray-900">{{ $user->name }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">البريد الإلكتروني</p>
                    <p class="text-lg text-gray-900">{{ $user->email }}</p>
                </div>
                
                @if($user->phone)
                <div>
                    <p class="text-sm font-medium text-gray-500">رقم الهاتف</p>
                    <p class="text-lg text-gray-900">{{ $user->phone }}</p>
                </div>
                @endif
                
                <div>
                    <p class="text-sm font-medium text-gray-500">الدور</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($user->role === 'admin') bg-red-100 text-red-800
                        @elseif($user->role === 'staff') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800
                        @endif">
                        {{ $user->role_label }}
                    </span>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">حالة الحساب</p>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        @if($user->is_active) bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ $user->is_active ? 'نشط' : 'غير نشط' }}
                    </span>
                </div>
                
                @if($user->specialization)
                <div>
                    <p class="text-sm font-medium text-gray-500">التخصص</p>
                    <p class="text-lg text-gray-900">{{ $user->specialization }}</p>
                </div>
                @endif
                
                <div>
                    <p class="text-sm font-medium text-gray-500">تاريخ الإنشاء</p>
                    <p class="text-lg text-gray-900">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                </div>
                
                <div>
                    <p class="text-sm font-medium text-gray-500">آخر تحديث</p>
                    <p class="text-lg text-gray-900">{{ $user->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
            
            @if($user->notes)
            <div class="mt-6">
                <p class="text-sm font-medium text-blue-500">الملاحظات</p>
                <p class="text-lg text-gray-900 whitespace-pre-line">{{ $user->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- User Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-blue-500">المواعيد</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->appointments_count ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-blue-500">المهام</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->tasks_count ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="mr-4">
                        <p class="text-sm font-medium text-blue-500">الحالة</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $user->is_active ? 'نشط' : 'غير نشط' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">إجراءات سريعة</h3>
            <div class="flex flex-wrap gap-3">
                @if($user->id !== auth()->id())
                <form action="{{ route('users.toggle-status', $user) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-blue-100 font-bold py-2 px-4 rounded">
                        {{ $user->is_active ? 'إلغاء التفعيل' : 'تفعيل' }}
                    </button>
                </form>
                
                <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-blue-100 font-bold py-2 px-4 rounded" onclick="return confirm('هل أنت متأكد من حذف هذا المستخدم؟')">
                        حذف المستخدم
                    </button>
                </form>
                @endif
                
                <a href="{{ route('users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-600 text-blue-100 font-bold py-2 px-4 rounded">
                    تعديل المستخدم
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

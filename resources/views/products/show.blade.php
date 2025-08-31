@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">{{ $product->name }}</h1>
                <p class="text-gray-600 mt-2">تفاصيل المنتج</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('products.edit', $product) }}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تعديل
                </a>
                <a href="{{ route('products.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    العودة للقائمة
                </a>
            </div>
        </div>
    </div>

    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">معلومات المنتج</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">اسم المنتج</label>
                            <p class="text-gray-900">{{ $product->name }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الفئة</label>
                            <p class="text-gray-900">{{ $product->category }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">المورد</label>
                            <p class="text-gray-900">{{ $product->supplier->name ?? 'غير محدد' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">رمز المنتج</label>
                            <p class="text-gray-900">{{ $product->sku ?: 'غير محدد' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">سعر الشراء</label>
                            <p class="text-gray-900">{{ $product->purchase_price }} {{ $product->currency }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">سعر البيع</label>
                            <p class="text-gray-900">{{ $product->selling_price }} {{ $product->currency }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الكمية المتوفرة</label>
                            <p class="text-gray-900">{{ $product->stock_quantity }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الحد الأدنى للمخزون</label>
                            <p class="text-gray-900">{{ $product->min_stock_level ?: 'غير محدد' }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">الحالة</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $product->is_active ? 'نشط' : 'غير نشط' }}
                            </span>
                        </div>
                    </div>
                    
                    @if($product->description)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">الوصف</label>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $product->description }}</p>
                    </div>
                    @endif
                    
                    @if($product->notes)
                    <div class="mt-6">
                        <label class="block text-sm font-medium text-gray-500 mb-2">الملاحظات</label>
                        <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $product->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">إحصائيات المنتج</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">تاريخ الإنشاء</span>
                            <span class="text-gray-900 font-medium">{{ $product->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">آخر تحديث</span>
                            <span class="text-gray-900 font-medium">{{ $product->updated_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-600">الربح</span>
                            <span class="text-green-600 font-semibold">
                                {{ $product->selling_price - $product->purchase_price }} {{ $product->currency }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">إجراءات سريعة</h3>
                    <div class="space-y-3">
                        <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline-block w-full" 
                              onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg font-medium">
                                حذف المنتج
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

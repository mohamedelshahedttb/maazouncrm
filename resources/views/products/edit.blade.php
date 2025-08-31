@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">تعديل المنتج</h1>
        <p class="text-gray-600 mt-2">تعديل تفاصيل المنتج: {{ $product->name }}</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">اسم المنتج *</label>
                    <input type="text" name="name" id="name" required 
                           value="{{ old('name', $product->name) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">الفئة *</label>
                    <input type="text" name="category" id="category" required 
                           value="{{ old('category', $product->category) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('category')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="supplier_id" class="block text-sm font-medium text-gray-700 mb-2">المورد *</label>
                    <select name="supplier_id" id="supplier_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر المورد</option>
                        @foreach($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id', $product->supplier_id) == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('supplier_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">سعر الشراء *</label>
                    <input type="number" step="0.01" name="purchase_price" id="purchase_price" required 
                           value="{{ old('purchase_price', $product->purchase_price) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('purchase_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="selling_price" class="block text-sm font-medium text-gray-700 mb-2">سعر البيع *</label>
                    <input type="number" step="0.01" name="selling_price" id="selling_price" required 
                           value="{{ old('selling_price', $product->selling_price) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('selling_price')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">العملة *</label>
                    <select name="currency" id="currency" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر العملة</option>
                        <option value="EGP" {{ old('currency', $product->currency) == 'EGP' ? 'selected' : '' }}>جنيه مصري</option>
                        <option value="USD" {{ old('currency', $product->currency) == 'USD' ? 'selected' : '' }}>دولار أمريكي</option>
                        <option value="EUR" {{ old('currency', $product->currency) == 'EUR' ? 'selected' : '' }}>يورو</option>
                    </select>
                    @error('currency')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">الكمية المتوفرة *</label>
                    <input type="number" name="stock_quantity" id="stock_quantity" required 
                           value="{{ old('stock_quantity', $product->stock_quantity) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('stock_quantity')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="min_stock_level" class="block text-sm font-medium text-gray-700 mb-2">الحد الأدنى للمخزون</label>
                    <input type="number" name="min_stock_level" id="min_stock_level" 
                           value="{{ old('min_stock_level', $product->min_stock_level) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('min_stock_level')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">رمز المنتج</label>
                    <input type="text" name="sku" id="sku" 
                           value="{{ old('sku', $product->sku) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('sku')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">الحالة</label>
                    <select name="status" id="status" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="active" {{ old('status', $product->status) == 'active' ? 'selected' : '' }}>نشط</option>
                        <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>غير نشط</option>
                        <option value="discontinued" {{ old('status', $product->status) == 'discontinued' ? 'selected' : '' }}>متوقف</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف المنتج</label>
                    <textarea name="description" id="description" rows="3" 
                              placeholder="وصف مفصل للمنتج..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="images" class="block text-sm font-medium text-gray-700 mb-2">صور المنتج</label>
                    <input type="file" name="images[]" id="images" multiple accept="image/*" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-sm text-gray-500 mt-1">يمكنك اختيار أكثر من صورة. الحد الأقصى: 2 ميجابايت لكل صورة</p>
                    @error('images.*')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @if($product->getMedia('product_images')->count() > 0)
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">الصور الحالية</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($product->getMedia('product_images') as $media)
                        <div class="relative">
                            <img src="{{ $media->getUrl() }}" alt="صورة المنتج" class="w-full h-24 object-cover rounded-lg border">
                            <button type="button" onclick="removeImage({{ $media->id }})" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                ×
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="أي ملاحظات إضافية..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes', $product->notes) }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" 
                               {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="mr-2 text-sm font-medium text-gray-700">نشط</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('products.show', $product) }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تحديث المنتج
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function removeImage(mediaId) {
    if (confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        fetch(`/products/{{ $product->id }}/images/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
    }
}
</script>
@endsection

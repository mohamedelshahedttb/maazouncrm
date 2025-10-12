@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">إضافة محافظة</h1>

    <form method="POST" action="{{ route('governorates.store') }}" class="space-y-6 bg-white border rounded p-6">
        @csrf

        <div>
            <label class="block text-sm mb-1">اسم المحافظة</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2">
            @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm mb-1">رسوم ثابتة أساسية</label>
            <input type="number" name="base_fixed_fee" step="0.01" min="0" value="{{ old('base_fixed_fee') }}" class="w-full border rounded px-3 py-2">
            @error('base_fixed_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm mb-1">رسوم إضافية</label>
            <input type="number" name="added_fees" step="0.01" min="0" value="{{ old('added_fees') }}" class="w-full border rounded px-3 py-2">
            @error('added_fees')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm mb-1">نسبة المؤخر (٪) - اختياري</label>
            <input type="number" name="mahr_percentage" step="0.01" min="0" max="100" value="{{ old('mahr_percentage') }}" class="w-full border rounded px-3 py-2">
            @error('mahr_percentage')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <span>مفعل</span>
        </div>

        <div class="flex justify-end gap-3">
            <a href="{{ route('governorates.index') }}" class="px-4 py-2 border rounded">إلغاء</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">حفظ</button>
        </div>
    </form>
</div>
@endsection




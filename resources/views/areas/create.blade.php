@extends('layouts.app')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">إضافة منطقة</h1>

    <form method="POST" action="{{ route('areas.store') }}" class="space-y-6 bg-white border rounded p-6">
        @csrf

        <div>
            <label class="block text-sm mb-1">اسم المنطقة</label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full border rounded px-3 py-2">
            @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div>
            <label class="block text-sm mb-1">رسوم المواصلات</label>
            <input type="number" name="transportation_fee" step="0.01" min="0" value="{{ old('transportation_fee') }}" class="w-full border rounded px-3 py-2">
            @error('transportation_fee')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
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
            <a href="{{ route('areas.index') }}" class="px-4 py-2 border rounded">إلغاء</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded">حفظ</button>
        </div>
    </form>
</div>
@endsection



@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">المحافظات</h1>
        <a href="{{ route('governorates.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">إضافة محافظة</a>
    </div>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    <div class="bg-white border rounded">
        <table class="w-full text-right">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2">الاسم</th>
                    <th class="px-4 py-2">رسوم أساسية</th>
                    <th class="px-4 py-2">رسوم إضافية</th>
                    <th class="px-4 py-2">نسبة المؤخر٪</th>
                    <th class="px-4 py-2">الحالة</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>
            <tbody>
                @forelse($governorates as $g)
                <tr class="border-t">
                    <td class="px-4 py-2">{{ $g->name }}</td>
                    <td class="px-4 py-2">{{ number_format($g->base_fixed_fee, 2) }}</td>
                    <td class="px-4 py-2">{{ number_format($g->added_fees, 2) }}</td>
                    <td class="px-4 py-2">{{ $g->mahr_percentage !== null ? rtrim(rtrim(number_format($g->mahr_percentage, 2), '0'), '.') : '-' }}%</td>
                    <td class="px-4 py-2">{{ $g->is_active ? 'مفعل' : 'غير مفعل' }}</td>
                    <td class="px-4 py-2 text-left">
                        <a href="{{ route('governorates.edit', $g) }}" class="px-3 py-1 border rounded">تعديل</a>
                        <form method="POST" action="{{ route('governorates.destroy', $g) }}" class="inline" onsubmit="return confirm('حذف المحافظة؟')">
                            @csrf
                            @method('DELETE')
                            <button class="px-3 py-1 border rounded text-red-600">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">لا توجد بيانات</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div class="p-3">{{ $governorates->links() }}</div>
    </div>
</div>
@endsection



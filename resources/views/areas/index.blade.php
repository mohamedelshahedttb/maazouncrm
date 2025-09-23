@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold">المناطق</h1>
        <a href="{{ route('areas.create') }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">إضافة منطقة</a>
    </div>

    @livewire('areas-index')

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-50 text-green-700 rounded">{{ session('success') }}</div>
    @endif

    
</div>
@endsection



@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">تعديل الفاتورة</h1>
                <p class="text-gray-600 mt-2">رقم الفاتورة: {{ $invoice->invoice_number }}</p>
            </div>
            <div class="flex space-x-3 space-x-reverse">
                <a href="{{ route('invoices.show', $invoice) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium">عرض الفاتورة</a>
                <a href="{{ route('invoices.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-medium">العودة للقائمة</a>
            </div>
        </div>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('invoices.update', $invoice) }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client Selection -->
                <div class="md:col-span-2">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">اختر العميل *</label>
                    <select name="client_id" id="client_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <option value="">اختر العميل</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $invoice->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} - {{ $client->phone }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Service Selection -->
                <div class="md:col-span-2">
                    <label for="service_id" class="block text-sm font-medium text-gray-700 mb-2">اختر الخدمة *</label>
                    <select name="service_id" id="service_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                        <option value="">اختر الخدمة</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id', $invoice->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->name }} - {{ $service->price }} {{ $service->currency }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount Fields -->
                <div>
                    <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-2">المبلغ الفرعي *</label>
                    <input type="number" name="subtotal" id="subtotal" required 
                           min="0" step="0.01" value="{{ old('subtotal', $invoice->subtotal) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @error('subtotal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ الضريبة</label>
                    <input type="number" name="tax_amount" id="tax_amount" 
                           min="0" step="0.01" value="{{ old('tax_amount', $invoice->tax_amount) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @error('tax_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ الخصم</label>
                    <input type="number" name="discount_amount" id="discount_amount" 
                           min="0" step="0.01" value="{{ old('discount_amount', $invoice->discount_amount) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @error('discount_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">المبلغ الإجمالي</label>
                    <input type="text" id="total_amount" readonly 
                           value="{{ number_format($invoice->total_amount, 2) }} {{ $invoice->currency }}"
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                <!-- Date Fields -->
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الإصدار *</label>
                    <input type="date" name="issue_date" id="issue_date" required 
                           value="{{ old('issue_date', $invoice->issue_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @error('issue_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الاستحقاق *</label>
                    <input type="date" name="due_date" id="due_date" required 
                           value="{{ old('due_date', $invoice->due_date->format('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Notes -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea name="notes" id="notes" rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg"
                          placeholder="أدخل أي ملاحظات أو تفاصيل إضافية">{{ old('notes', $invoice->notes) }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex justify-end space-x-4 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('invoices.show', $invoice) }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                    إلغاء
                </a>
                <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-white rounded-lg font-medium">
                    تحديث الفاتورة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

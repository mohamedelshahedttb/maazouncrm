@extends('layouts.app')

@section('content')
<div class="p-6">
    <!-- Page Title -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">إنشاء فاتورة جديدة</h1>
        <p class="text-gray-600 mt-2">إنشاء فاتورة جديدة للعميل</p>
    </div>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('invoices.store') }}" method="POST" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Client Selection -->
                <div class="md:col-span-2">
                    <label for="client_id" class="block text-sm font-medium text-gray-700 mb-2">اختر العميل *</label>
                    <select name="client_id" id="client_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر العميل</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $selectedClientId) == $client->id ? 'selected' : '' }}>
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
                    <select name="service_id" id="service_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">اختر الخدمة</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }} data-price="{{ $service->price }}">
                                {{ $service->name }} - {{ $service->price }} {{ $service->currency }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Appointment Selection (Optional) -->
                <div class="md:col-span-2">
                    <label for="appointment_id" class="block text-sm font-medium text-gray-700 mb-2">الموعد (اختياري)</label>
                    <select name="appointment_id" id="appointment_id" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="">بدون موعد</option>
                        @foreach($appointments as $appointment)
                            <option value="{{ $appointment->id }}" {{ old('appointment_id') == $appointment->id ? 'selected' : '' }}>
                                {{ $appointment->client->name }} - {{ $appointment->service->name }} - {{ $appointment->appointment_date->format('Y-m-d H:i') }}
                            </option>
                        @endforeach
                    </select>
                    @error('appointment_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Amount Fields -->
                <div>
                    <label for="subtotal" class="block text-sm font-medium text-gray-700 mb-2">المبلغ الفرعي *</label>
                    <input type="number" name="subtotal" id="subtotal" required 
                           min="0" step="0.01" value="{{ old('subtotal') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('subtotal')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tax_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ الضريبة</label>
                    <input type="number" name="tax_amount" id="tax_amount" 
                           min="0" step="0.01" value="{{ old('tax_amount', 0) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('tax_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="discount_amount" class="block text-sm font-medium text-gray-700 mb-2">مبلغ الخصم</label>
                    <input type="number" name="discount_amount" id="discount_amount" 
                           min="0" step="0.01" value="{{ old('discount_amount', 0) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('discount_amount')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">المبلغ الإجمالي</label>
                    <input type="text" id="total_amount" readonly 
                           class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg">
                </div>

                <!-- Date Fields -->
                <div>
                    <label for="issue_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الإصدار *</label>
                    <input type="date" name="issue_date" id="issue_date" required 
                           value="{{ old('issue_date', date('Y-m-d')) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('issue_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">تاريخ الاستحقاق *</label>
                    <input type="date" name="due_date" id="due_date" required 
                           value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('due_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Payment Terms -->
                <div>
                    <label for="payment_terms" class="block text-sm font-medium text-gray-700 mb-2">شروط الدفع</label>
                    <input type="text" name="payment_terms" id="payment_terms" 
                           value="{{ old('payment_terms', '30 يوم') }}"
                           placeholder="30 يوم"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('payment_terms')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Billing Address -->
                <div>
                    <label for="billing_address" class="block text-sm font-medium text-gray-700 mb-2">عنوان الفواتير</label>
                    <input type="text" name="billing_address" id="billing_address" 
                           value="{{ old('billing_address') }}"
                           placeholder="عنوان العميل للفواتير"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('billing_address')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                    <textarea name="notes" id="notes" rows="3" 
                              placeholder="أي ملاحظات إضافية حول الفاتورة..."
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">{{ old('notes') }}</textarea>
                    @error('notes')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 space-x-reverse mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('invoices.index') }}" class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg font-medium hover:bg-gray-50">
                    إلغاء
                </a>
                                            <button type="submit" class="px-6 py-3 bg-blue-500 hover:bg-blue-600 text-blue-100 rounded-lg font-medium hover:text-white">
                                إنشاء الفاتورة
                            </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotalInput = document.getElementById('subtotal');
    const taxInput = document.getElementById('tax_amount');
    const discountInput = document.getElementById('discount_amount');
    const totalInput = document.getElementById('total_amount');
    const serviceSelect = document.getElementById('service_id');

    function calculateTotal() {
        const subtotal = parseFloat(subtotalInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        const discount = parseFloat(discountInput.value) || 0;
        
        const total = subtotal + tax - discount;
        totalInput.value = total.toFixed(2);
    }

    // Auto-fill subtotal when service is selected
    serviceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.dataset.price;
        if (price) {
            subtotalInput.value = price;
            calculateTotal();
        }
    });

    // Calculate total when amounts change
    subtotalInput.addEventListener('input', calculateTotal);
    taxInput.addEventListener('input', calculateTotal);
    discountInput.addEventListener('input', calculateTotal);

    // Initial calculation
    calculateTotal();
});
</script>
@endsection

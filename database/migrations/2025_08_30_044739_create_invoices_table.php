<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // رقم الفاتورة
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // العميل
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // الخدمة
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null'); // الموعد المرتبط
            $table->decimal('subtotal', 10, 2); // المجموع الفرعي
            $table->decimal('tax_amount', 10, 2)->default(0); // مبلغ الضريبة
            $table->decimal('discount_amount', 10, 2)->default(0); // مبلغ الخصم
            $table->decimal('total_amount', 10, 2); // المجموع الكلي
            $table->string('currency', 3)->default('EGP'); // العملة
            $table->enum('status', ['draft', 'sent', 'paid', 'overdue', 'cancelled'])->default('draft'); // حالة الفاتورة
            $table->date('issue_date'); // تاريخ الإصدار
            $table->date('due_date'); // تاريخ الاستحقاق
            $table->date('paid_date')->nullable(); // تاريخ الدفع
            $table->text('notes')->nullable(); // ملاحظات
            $table->text('payment_terms')->nullable(); // شروط الدفع
            $table->string('payment_method')->nullable(); // طريقة الدفع
            $table->text('billing_address')->nullable(); // عنوان الفواتير
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            $table->index(['client_id', 'status']);
            $table->index(['invoice_number']);
            $table->index(['due_date', 'status']);
            $table->index(['issue_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

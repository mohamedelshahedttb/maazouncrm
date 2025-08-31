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
        Schema::create('client_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // العميل
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // الخدمة
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('set null'); // الموعد المرتبط
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending'); // حالة الطلب
            $table->decimal('total_amount', 10, 2); // المبلغ الإجمالي
            $table->decimal('paid_amount', 10, 2)->default(0); // المبلغ المدفوع
            $table->string('currency', 3)->default('EGP'); // العملة
            $table->date('expected_completion_date')->nullable(); // تاريخ الإنجاز المتوقع
            $table->date('actual_completion_date')->nullable(); // تاريخ الإنجاز الفعلي
            $table->text('requirements')->nullable(); // المتطلبات
            $table->text('notes')->nullable(); // ملاحظات
            $table->text('special_instructions')->nullable(); // تعليمات خاصة
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['client_id', 'status']);
            $table->index(['service_id', 'status']);
            $table->index(['status', 'expected_completion_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_orders');
    }
};

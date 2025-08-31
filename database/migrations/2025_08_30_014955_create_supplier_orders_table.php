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
        Schema::create('supplier_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->onDelete('cascade'); // المورد
            $table->string('order_number')->unique(); // رقم الطلب
            $table->text('description'); // وصف الطلب
            $table->integer('quantity'); // الكمية
            $table->decimal('unit_price', 10, 2); // سعر الوحدة
            $table->decimal('total_amount', 10, 2); // المبلغ الإجمالي
            $table->string('currency', 3)->default('EGP'); // العملة
            $table->date('expected_delivery_date'); // تاريخ التسليم المتوقع
            $table->date('actual_delivery_date')->nullable(); // تاريخ التسليم الفعلي
            $table->enum('status', ['pending', 'confirmed', 'in_progress', 'delivered', 'cancelled'])->default('pending'); // حالة الطلب
            $table->text('notes')->nullable(); // ملاحظات
            $table->text('quality_notes')->nullable(); // ملاحظات الجودة
            $table->text('delivery_notes')->nullable(); // ملاحظات التسليم
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['supplier_id', 'status']);
            $table->index(['status', 'expected_delivery_date']);
            $table->index('order_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplier_orders');
    }
};

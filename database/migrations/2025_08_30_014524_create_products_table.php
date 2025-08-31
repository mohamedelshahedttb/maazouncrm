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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المنتج
            $table->string('category'); // الفئة
            $table->text('description')->nullable(); // الوصف
            $table->foreignId('supplier_id')->nullable()->constrained()->onDelete('set null'); // المورد المرتبط
            $table->decimal('purchase_price', 10, 2); // سعر الشراء
            $table->decimal('selling_price', 10, 2); // سعر البيع
            $table->string('currency', 3)->default('EGP'); // العملة
            $table->integer('stock_quantity')->default(0); // كمية المخزون
            $table->integer('min_stock_level')->default(10); // الحد الأدنى للمخزون
            $table->string('sku')->nullable()->unique(); // رمز المنتج
            $table->enum('status', ['active', 'inactive', 'discontinued'])->default('active'); // الحالة
            $table->text('notes')->nullable(); // ملاحظات
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'is_active']);
            $table->index(['supplier_id', 'status']);
            $table->index('stock_quantity');
            $table->index('sku');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

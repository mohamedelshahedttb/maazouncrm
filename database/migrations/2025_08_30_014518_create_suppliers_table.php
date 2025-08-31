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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المورد
            $table->string('contact_person')->nullable(); // جهة الاتصال
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            $table->text('services_products'); // الخدمات/المنتجات المقدمة
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); // الحالة
            $table->text('notes')->nullable(); // ملاحظات
            $table->decimal('rating', 3, 2)->nullable(); // التقييم
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'is_active']);
            $table->index('name');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};

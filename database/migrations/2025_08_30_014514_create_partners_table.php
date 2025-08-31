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
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الشريك
            $table->string('license_number')->unique(); // رقم الترخيص
            $table->text('service_scope'); // نطاق الخدمات
            $table->string('phone')->nullable(); // رقم الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            $table->decimal('commission_rate', 5, 2)->default(0); // نسبة العمولة
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active'); // الحالة
            $table->text('notes')->nullable(); // ملاحظات
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'is_active']);
            $table->index('license_number');
            $table->index('commission_rate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};

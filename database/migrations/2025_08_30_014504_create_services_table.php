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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الخدمة
            $table->string('category'); // فئة الخدمة (زواج، طلاق، تصديق، إلخ)
            $table->text('description')->nullable(); // وصف الخدمة
            $table->decimal('price', 10, 2); // سعر الخدمة
            $table->string('currency', 3)->default('EGP'); // العملة
            $table->integer('duration_minutes')->nullable(); // المدة الزمنية بالدقائق
            $table->text('requirements')->nullable(); // المتطلبات الخاصة
            $table->text('notes')->nullable(); // ملاحظات
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['category', 'is_active']);
            $table->index('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};

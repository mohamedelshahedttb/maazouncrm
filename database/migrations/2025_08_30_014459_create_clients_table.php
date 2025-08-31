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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم العميل
            $table->string('bride_name')->nullable(); // اسم العروسه
            $table->string('guardian_name')->nullable(); // اسم ولي العروسه
            $table->string('phone'); // رقم الهاتف
            $table->string('email')->nullable(); // البريد الإلكتروني
            $table->text('address')->nullable(); // العنوان
            $table->enum('status', ['new', 'in_progress', 'completed', 'cancelled'])->default('new'); // حالة العميل
            $table->text('notes')->nullable(); // ملاحظات
            $table->string('whatsapp_number')->nullable(); // رقم الواتس اب
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'is_active']);
            $table->index('phone');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};

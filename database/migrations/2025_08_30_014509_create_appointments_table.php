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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // العميل
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // الخدمة
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // المأذون المسؤول
            $table->dateTime('appointment_date'); // موعد الموعد
            $table->dateTime('end_time')->nullable(); // وقت الانتهاء
            $table->string('location')->nullable(); // الموقع
            $table->enum('status', ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'rescheduled'])->default('scheduled'); // حالة الموعد
            $table->text('notes')->nullable(); // ملاحظات
            $table->text('requirements')->nullable(); // المتطلبات
            $table->boolean('whatsapp_reminder_sent')->default(false); // تم إرسال تذكير الواتس اب
            $table->timestamp('reminder_sent_at')->nullable(); // وقت إرسال التذكير
            $table->timestamps();
            
            // Indexes
            $table->index(['appointment_date', 'status']);
            $table->index(['client_id', 'status']);
            $table->index('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

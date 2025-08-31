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
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade'); // العميل
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // المستخدم المسؤول
            $table->enum('type', ['phone', 'whatsapp', 'email', 'in_person', 'other'])->default('phone'); // نوع المحادثة
            $table->text('content'); // محتوى المحادثة
            $table->text('summary')->nullable(); // ملخص المحادثة
            $table->enum('direction', ['incoming', 'outgoing'])->default('incoming'); // اتجاه المحادثة
            $table->string('phone_number')->nullable(); // رقم الهاتف المستخدم
            $table->string('whatsapp_number')->nullable(); // رقم الواتس اب
            $table->string('email_address')->nullable(); // عنوان البريد الإلكتروني
            $table->enum('status', ['active', 'resolved', 'pending_followup'])->default('active'); // الحالة
            $table->dateTime('conversation_date'); // تاريخ المحادثة
            $table->text('follow_up_notes')->nullable(); // ملاحظات المتابعة
            $table->date('follow_up_date')->nullable(); // تاريخ المتابعة
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['client_id', 'conversation_date']);
            $table->index(['type', 'status']);
            $table->index('conversation_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};

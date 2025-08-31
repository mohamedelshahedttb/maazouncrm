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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان المهمة
            $table->text('description')->nullable(); // وصف المهمة
            $table->foreignId('appointment_id')->nullable()->constrained()->onDelete('cascade'); // الموعد المرتبط
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // المسؤول عن المهمة
            $table->foreignId('partner_id')->nullable()->constrained()->onDelete('set null'); // الشريك المسؤول
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium'); // الأولوية
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled', 'delayed'])->default('pending'); // الحالة
            $table->dateTime('due_date')->nullable(); // تاريخ الاستحقاق
            $table->dateTime('started_at')->nullable(); // وقت البدء
            $table->dateTime('completed_at')->nullable(); // وقت الإكمال
            $table->text('execution_notes')->nullable(); // ملاحظات التنفيذ
            $table->text('location')->nullable(); // الموقع
            $table->text('changes_notes')->nullable(); // التغييرات أو الملاحظات
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'priority']);
            $table->index(['assigned_to', 'status']);
            $table->index(['appointment_id', 'status']);
            $table->index('due_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};

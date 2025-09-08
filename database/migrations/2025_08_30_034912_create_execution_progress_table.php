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
        Schema::create('execution_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained()->onDelete('cascade'); // الموعد
            $table->foreignId('execution_step_id')->constrained('service_execution_steps')->onDelete('cascade'); // خطوة التنفيذ
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null'); // المهمة المرتبطة
            $table->enum('status', ['pending', 'in_progress', 'completed', 'blocked', 'skipped'])->default('pending'); // الحالة
            $table->timestamp('started_at')->nullable(); // وقت البدء
            $table->timestamp('completed_at')->nullable(); // وقت الإكمال
            $table->text('execution_notes')->nullable(); // ملاحظات التنفيذ
            $table->text('blocking_reason')->nullable(); // سبب التعطيل
            $table->foreignId('assigned_to')->nullable()->constrained('users')->onDelete('set null'); // المسؤول
            $table->foreignId('assigned_partner_id')->nullable()->constrained('partners')->onDelete('set null'); // الالشيخ المسؤول
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['appointment_id', 'status']);
            $table->index(['execution_step_id', 'status']);
            $table->index(['assigned_to', 'status']);
            $table->index(['assigned_partner_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('execution_progress');
    }
};

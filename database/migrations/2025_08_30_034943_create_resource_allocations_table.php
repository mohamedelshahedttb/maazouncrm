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
        Schema::create('resource_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->nullable()->constrained()->onDelete('set null'); // المهمة
            $table->foreignId('execution_progress_id')->nullable()->constrained('execution_progress')->onDelete('set null'); // تقدم التنفيذ
            $table->enum('resource_type', ['partner', 'supplier', 'equipment', 'location', 'product'])->default('partner'); // نوع المورد
            $table->unsignedBigInteger('resource_id'); // معرف المورد
            $table->string('resource_name'); // اسم المورد
            $table->timestamp('allocated_from'); // وقت التخصيص من
            $table->timestamp('allocated_until')->nullable(); // وقت التخصيص حتى
            $table->enum('status', ['allocated', 'in_use', 'released', 'overdue'])->default('allocated'); // الحالة
            $table->text('allocation_notes')->nullable(); // ملاحظات التخصيص
            $table->text('release_notes')->nullable(); // ملاحظات الإفراج
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['task_id', 'resource_type']);
            $table->index(['execution_progress_id', 'resource_type']);
            $table->index(['resource_type', 'resource_id']);
            $table->index(['status', 'allocated_from']);
            $table->index(['allocated_from', 'allocated_until']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resource_allocations');
    }
};

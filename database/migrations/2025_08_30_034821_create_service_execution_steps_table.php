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
        Schema::create('service_execution_steps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // الخدمة
            $table->integer('step_order'); // ترتيب الخطوة
            $table->string('step_name'); // اسم الخطوة
            $table->text('step_description')->nullable(); // وصف الخطوة
            $table->integer('estimated_duration_minutes')->nullable(); // المدة المتوقعة بالدقائق
            $table->text('required_resources')->nullable(); // الموارد المطلوبة
            $table->text('dependencies')->nullable(); // الخطوات المطلوبة قبل هذه الخطوة
            $table->enum('step_type', ['preparation', 'execution', 'verification', 'delivery'])->default('execution'); // نوع الخطوة
            $table->boolean('is_required')->default(true); // هل الخطوة مطلوبة
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['service_id', 'step_order']);
            $table->index(['step_type', 'is_active']);
            $table->unique(['service_id', 'step_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_execution_steps');
    }
};

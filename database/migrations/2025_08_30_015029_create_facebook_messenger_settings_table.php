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
        Schema::create('facebook_messenger_settings', function (Blueprint $table) {
            $table->id();
            $table->string('page_name'); // اسم الصفحة
            $table->string('page_id')->unique(); // معرف الصفحة
            $table->string('access_token')->nullable(); // رمز الوصول
            $table->string('webhook_url')->nullable(); // رابط الويبهوك
            $table->string('verify_token')->nullable(); // رمز التحقق
            $table->enum('status', ['active', 'inactive', 'pending_verification'])->default('pending_verification'); // الحالة
            $table->json('message_templates')->nullable(); // قوالب الرسائل
            $table->json('auto_replies')->nullable(); // الردود التلقائية
            $table->boolean('appointment_reminders')->default(true); // تذكيرات المواعيد
            $table->boolean('follow_up_messages')->default(true); // رسائل المتابعة
            $table->text('notes')->nullable(); // ملاحظات
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['status', 'is_active']);
            $table->index('page_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facebook_messenger_settings');
    }
};

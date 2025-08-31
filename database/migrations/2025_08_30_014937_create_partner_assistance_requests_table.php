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
        Schema::create('partner_assistance_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requesting_partner_id')->constrained('partners')->onDelete('cascade'); // الشريك الطالب
            $table->foreignId('assisting_partner_id')->constrained('partners')->onDelete('cascade'); // الشريك المساعد
            $table->string('service_type'); // نوع الخدمة المطلوبة
            $table->dateTime('requested_date_time'); // التاريخ والوقت المطلوب
            $table->string('location'); // الموقع
            $table->text('description')->nullable(); // وصف الطلب
            $table->enum('status', ['pending', 'accepted', 'in_progress', 'completed', 'rejected'])->default('pending'); // حالة الطلب
            $table->decimal('commission_amount', 10, 2)->nullable(); // مبلغ العمولة
            $table->text('notes')->nullable(); // ملاحظات
            $table->dateTime('accepted_at')->nullable(); // وقت القبول
            $table->dateTime('completed_at')->nullable(); // وقت الإكمال
            $table->boolean('is_active')->default(true); // نشط
            $table->timestamps();
            
            // Indexes
            $table->index(['requesting_partner_id', 'status']);
            $table->index(['assisting_partner_id', 'status']);
            $table->index(['status', 'requested_date_time']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partner_assistance_requests');
    }
};

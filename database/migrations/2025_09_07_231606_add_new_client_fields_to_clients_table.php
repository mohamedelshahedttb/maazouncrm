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
        Schema::table('clients', function (Blueprint $table) {
            // Geographical area
            $table->string('geographical_area')->nullable()->after('address'); // المنطقة الجغرافية
            
            // Call result and follow-up
            $table->enum('call_result', [
                'interested',           // مهتم
                'not_interested',       // غير مهتم
                'follow_up_later',      // متابعة لاحقا
                'potential_client',     // عميل محتمل
                'confirmed_booking',    // حجز مؤكد
                'completed_booking',    // حجز مكتمل
                'cancelled',           // ملغي
                'inquiry',             // استفسار
                'client_booking',      // حجز العميل
                'no_answer',           // لم يتم الرد
                'busy_number'          // الرقم مشغول
            ])->nullable()->after('status'); // نتيجة المكالمة
            
            $table->date('next_follow_up_date')->nullable()->after('call_result'); // تاريخ المتابعة التالية
            
            // Relationship status
            $table->string('relationship_status')->nullable()->after('next_follow_up_date'); // صلة القرابة ولي العروسة
            
            // Location information
            $table->text('google_maps_link')->nullable()->after('relationship_status'); // رابط الموقع من خرائط جوجل
            $table->string('governorate')->nullable()->after('google_maps_link'); // المحافظة
            $table->string('area')->nullable()->after('governorate'); // المنطقة
            
            // Document workflow fields
            $table->enum('document_status', [
                'pending',              // في الانتظار
                'under_review',         // قيد المراجعة
                'approved',             // موافق عليه
                'rejected',             // مرفوض
                'incomplete'            // الاوراق غير مكتملة
            ])->default('pending')->after('area'); // حالة المستندات
            
            $table->text('document_rejection_reason')->nullable()->after('document_status'); // سبب رفض المستندات
            
            // Operation assignment fields
            $table->unsignedBigInteger('assigned_partner_id')->nullable()->after('document_rejection_reason'); // الالشيخ المكلف
            $table->date('job_date')->nullable()->after('assigned_partner_id'); // تاريخ العمل
            $table->time('job_time')->nullable()->after('job_date'); // وقت العمل
            $table->string('job_number')->nullable()->after('job_time'); // رقم الفتر
            $table->string('coupon_number')->nullable()->after('job_number'); // رقم القسيمة
            
            // Final document delivery
            $table->date('final_document_delivery_date')->nullable()->after('coupon_number'); // تاريخ تسليم المستندات النهائية
            $table->boolean('final_document_notification_sent')->default(false)->after('final_document_delivery_date'); // تم إرسال إشعار تسليم المستندات
            
            // Add foreign key for assigned partner
            $table->foreign('assigned_partner_id')->references('id')->on('partners')->onDelete('set null');
            
            // Add indexes
            $table->index(['call_result', 'next_follow_up_date']);
            $table->index(['document_status', 'assigned_partner_id']);
            $table->index(['governorate', 'area']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['assigned_partner_id']);
            $table->dropIndex(['call_result', 'next_follow_up_date']);
            $table->dropIndex(['document_status', 'assigned_partner_id']);
            $table->dropIndex(['governorate', 'area']);
            
            $table->dropColumn([
                'geographical_area',
                'call_result',
                'next_follow_up_date',
                'relationship_status',
                'google_maps_link',
                'governorate',
                'area',
                'document_status',
                'document_rejection_reason',
                'assigned_partner_id',
                'job_date',
                'job_time',
                'job_number',
                'coupon_number',
                'final_document_delivery_date',
                'final_document_notification_sent'
            ]);
        });
    }
};
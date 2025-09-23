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
            // Contract details
            $table->string('contract_location')->nullable()->after('event_date'); // مكان العقد
            $table->decimal('contract_cost', 10, 2)->nullable()->after('contract_location'); // تكلفة العقد
            $table->text('contract_address')->nullable()->after('contract_cost'); // عنوان العقد بالتفصيل
            
            // Document details
            $table->string('temporary_document')->nullable()->after('contract_address'); // الوثيقة المؤقتة
            $table->string('sheikh_name')->nullable()->after('temporary_document'); // اسم الشيخ
            $table->string('book_number')->nullable()->after('sheikh_name'); // رقم الدفتر
            $table->string('document_number')->nullable()->after('book_number'); // رقم الوثيقة
            
            // Delivery details
            $table->date('coupon_arrival_date')->nullable()->after('document_number'); // تاريخ وصول القسيمة
            $table->date('document_receipt_date')->nullable()->after('coupon_arrival_date'); // تاريخ استلام الوثيقة
            $table->enum('document_receiver', ['delivery', 'client', 'client_relative'])->nullable()->after('document_receipt_date'); // مستلم الوثيقة
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'contract_location',
                'contract_cost',
                'contract_address',
                'temporary_document',
                'sheikh_name',
                'book_number',
                'document_number',
                'coupon_arrival_date',
                'document_receipt_date',
                'document_receiver'
            ]);
        });
    }
};
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
            // Add discount fields
            $table->enum('discount_type', ['percentage', 'fixed_amount'])->nullable()->after('contract_cost'); // نوع الخصم
            $table->decimal('discount_value', 10, 2)->nullable()->after('discount_type'); // قيمة الخصم
            $table->decimal('final_price', 10, 2)->nullable()->after('discount_value'); // السعر النهائي
            
            // Add accessories field (JSON to store selected product IDs)
            $table->json('accessories')->nullable()->after('bride_age'); // اكسسوارات العقد
            
            // Add client status field (re-add if it was removed)
            $table->enum('client_status', ['new', 'in_progress', 'completed', 'cancelled'])->default('new')->after('next_follow_up_date'); // حالة العميل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'discount_type',
                'discount_value', 
                'final_price',
                'accessories',
                'client_status'
            ]);
        });
    }
};

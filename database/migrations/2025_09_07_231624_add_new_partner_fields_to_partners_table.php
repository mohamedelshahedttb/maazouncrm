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
        Schema::table('partners', function (Blueprint $table) {
            // Office information
            $table->string('office_name')->nullable()->after('name'); // اسم المكتب
            $table->text('office_address')->nullable()->after('office_name'); // عنوان المكتب
            
            // Agent information
            $table->string('agent_name')->nullable()->after('office_address'); // الوكيل من المأذون
            $table->string('agent_phone')->nullable()->after('agent_name'); // رقم هاتف الوكيل
            $table->string('agent_email')->nullable()->after('agent_phone'); // بريد الوكيل الإلكتروني
            
            // Location and document numbers
            $table->string('location_number')->nullable()->after('agent_email'); // موقع
            $table->string('book_number')->nullable()->after('location_number'); // رقم الدفتر
            $table->string('document_number')->nullable()->after('book_number'); // رقم الوثيقه
            
            // Add indexes
            $table->index('office_name');
            $table->index('agent_name');
            $table->index(['location_number', 'book_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners', function (Blueprint $table) {
            $table->dropIndex(['location_number', 'book_number']);
            $table->dropIndex('agent_name');
            $table->dropIndex('office_name');
            
            $table->dropColumn([
                'office_name',
                'office_address',
                'agent_name',
                'agent_phone',
                'agent_email',
                'location_number',
                'book_number',
                'document_number'
            ]);
        });
    }
};
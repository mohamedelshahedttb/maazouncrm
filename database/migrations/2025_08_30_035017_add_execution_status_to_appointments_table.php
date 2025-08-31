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
        Schema::table('appointments', function (Blueprint $table) {
            $table->enum('execution_status', ['scheduled', 'in_execution', 'completed', 'cancelled'])->default('scheduled')->after('status');
            $table->timestamp('execution_started_at')->nullable()->after('end_time');
            $table->timestamp('execution_completed_at')->nullable()->after('execution_started_at');
            $table->text('execution_notes')->nullable()->after('requirements');
            $table->foreignId('primary_partner_id')->nullable()->constrained('partners')->onDelete('set null')->after('assigned_to');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign(['primary_partner_id']);
            $table->dropColumn([
                'execution_status',
                'execution_started_at',
                'execution_completed_at',
                'execution_notes',
                'primary_partner_id'
            ]);
        });
    }
};

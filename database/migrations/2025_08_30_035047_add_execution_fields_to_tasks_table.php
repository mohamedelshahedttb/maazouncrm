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
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('execution_step_id')->nullable()->constrained('service_execution_steps')->onDelete('set null')->after('partner_id');
            $table->foreignId('resource_allocation_id')->nullable()->constrained('resource_allocations')->onDelete('set null')->after('execution_step_id');
            $table->enum('execution_phase', ['preparation', 'execution', 'verification', 'delivery'])->default('execution')->after('status');
            $table->text('prerequisites')->nullable()->after('description');
            $table->text('deliverables')->nullable()->after('execution_notes');
            $table->decimal('estimated_cost', 10, 2)->nullable()->after('deliverables');
            $table->string('cost_currency', 3)->default('EGP')->after('estimated_cost');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['execution_step_id']);
            $table->dropForeign(['resource_allocation_id']);
            $table->dropColumn([
                'execution_step_id',
                'resource_allocation_id',
                'execution_phase',
                'prerequisites',
                'deliverables',
                'estimated_cost',
                'cost_currency'
            ]);
        });
    }
};

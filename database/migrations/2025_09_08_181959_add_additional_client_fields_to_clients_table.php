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
            $table->integer('bride_age')->nullable()->after('relationship_status');
            $table->date('event_date')->nullable()->after('bride_age');
            $table->string('mahr')->nullable()->after('event_date');
            $table->text('bride_id_address')->nullable()->after('mahr');
            $table->enum('contract_delivery_method', ['delivery', 'office'])->nullable()->after('bride_id_address');
            $table->date('contract_delivery_date')->nullable()->after('contract_delivery_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'bride_age',
                'event_date', 
                'mahr',
                'bride_id_address',
                'contract_delivery_method',
                'contract_delivery_date'
            ]);
        });
    }
};

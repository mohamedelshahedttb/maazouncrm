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
            $table->string('delivery_man_name')->nullable()->after('document_receiver'); // اسم الدليفري
            $table->string('client_relative_name')->nullable()->after('delivery_man_name'); // اسم قريب العميل
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['delivery_man_name', 'client_relative_name']);
        });
    }
};

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
        // Add missing fields to WhatsApp settings table
        Schema::table('whatsapp_settings', function (Blueprint $table) {
            $table->string('verify_token')->nullable()->after('webhook_url');
            $table->string('app_secret')->nullable()->after('verify_token');
        });

        // Add missing fields to Facebook Messenger settings table
        Schema::table('facebook_messenger_settings', function (Blueprint $table) {
            $table->string('app_secret')->nullable()->after('verify_token');
        });

        // Add missing fields to clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->string('facebook_id')->nullable()->after('whatsapp_number');
            $table->string('facebook_page_id')->nullable()->after('facebook_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove fields from WhatsApp settings table
        Schema::table('whatsapp_settings', function (Blueprint $table) {
            $table->dropColumn(['verify_token', 'app_secret']);
        });

        // Remove fields from Facebook Messenger settings table
        Schema::table('facebook_messenger_settings', function (Blueprint $table) {
            $table->dropColumn(['app_secret']);
        });

        // Remove fields from clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['facebook_id', 'facebook_page_id']);
        });
    }
};

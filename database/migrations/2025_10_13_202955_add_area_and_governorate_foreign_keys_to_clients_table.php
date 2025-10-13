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
        // Add foreign key columns for area and governorate only if they don't exist
        if (!Schema::hasColumn('clients', 'area_id')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->unsignedBigInteger('area_id')->nullable()->after('source_id');
            });
        }
        
        if (!Schema::hasColumn('clients', 'governorate_id')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->unsignedBigInteger('governorate_id')->nullable()->after('area_id');
            });
        }
        
        // Add foreign key constraints (SQLite will handle duplicates gracefully)
        try {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist, continue
        }
        
        try {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('set null');
            });
        } catch (\Exception $e) {
            // Foreign key might already exist, continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['area_id']);
            $table->dropForeign(['governorate_id']);
            
            // Then drop the columns
            $table->dropColumn(['area_id', 'governorate_id']);
        });
    }
};

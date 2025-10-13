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
        
        // Add foreign key constraints only if they don't exist
        if (Schema::hasColumn('clients', 'area_id') && !$this->foreignKeyExists('clients', 'clients_area_id_foreign')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreign('area_id')->references('id')->on('areas')->onDelete('set null');
            });
        }
        
        if (Schema::hasColumn('clients', 'governorate_id') && !$this->foreignKeyExists('clients', 'clients_governorate_id_foreign')) {
            Schema::table('clients', function (Blueprint $table) {
                $table->foreign('governorate_id')->references('id')->on('governorates')->onDelete('set null');
            });
        }
    }
    
    /**
     * Check if a foreign key constraint exists
     */
    private function foreignKeyExists(string $table, string $constraint): bool
    {
        $foreignKeys = Schema::getConnection()
            ->getDoctrineSchemaManager()
            ->listTableForeignKeys($table);
            
        foreach ($foreignKeys as $foreignKey) {
            if ($foreignKey->getName() === $constraint) {
                return true;
            }
        }
        
        return false;
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

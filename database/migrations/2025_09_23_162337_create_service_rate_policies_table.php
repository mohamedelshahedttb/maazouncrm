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
        Schema::create('service_rate_policies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            // Inclusive ranges for mahr
            $table->decimal('mahr_min', 10, 2)->nullable();
            $table->decimal('mahr_max', 10, 2)->nullable();
            $table->decimal('fixed_fee', 10, 2); // e.g., 1800
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['service_id', 'mahr_min', 'mahr_max']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_rate_policies');
    }
};

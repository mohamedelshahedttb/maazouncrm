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
        Schema::create('areas', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم المنطقة
            $table->decimal('transportation_fee', 10, 2)->default(0); // رسوم المواصلات
            $table->decimal('mahr_percentage', 5, 2)->nullable(); // نسبة من المؤخر إن وجدت
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('areas');
    }
};

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
        Schema::create('required_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained()->onDelete('cascade'); // الخدمة
            $table->string('document_name'); // اسم المستند
            $table->text('description')->nullable(); // وصف المستند
            $table->enum('document_type', ['required', 'optional', 'conditional'])->default('required'); // نوع المستند
            $table->string('file_format')->nullable(); // صيغة الملف المطلوبة
            $table->integer('max_file_size_mb')->default(10); // الحد الأقصى لحجم الملف
            $table->boolean('is_active')->default(true); // نشط
            $table->integer('sort_order')->default(0); // ترتيب المستند
            $table->timestamps();
            
            $table->index(['service_id', 'is_active']);
            $table->index(['document_type', 'is_active']);
            $table->index(['sort_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('required_documents');
    }
};

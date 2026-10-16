<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employee_documents', function (Blueprint $table) {
            $table->id();

            // Each document belongs to an employee.
            $table->foreignId('employee_id')
                ->constrained('employees')
                ->cascadeOnDelete();

            // The name of the uploaded file before storage.
            $table->string('original_name');

            // The path Laravel uses to locate the stored file.
            $table->string('path')->unique();

            // Store metadata for displaying document information.
            $table->string('mime_type');
            $table->unsignedBigInteger('size');

            // The authenticated user who uploaded the document.
            $table->foreignId('uploaded_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employee_documents');
    }
};

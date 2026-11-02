<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */

    //  An expense_claims table: employee_id, category, amount (decimal), expense_date, description (nullable), receipt_path (nullable), status (pending, approved or rejected; default pending), reviewed_by (user id, nullable), reviewed_at (nullable), rejection_reason (nullable), timestamps. Use foreign keys.

    public function up(): void
    {
        Schema::create('expense_claims', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')
                ->constrained('employees');
            $table->string('category');
            $table->decimal('amount', 10, 2);
            $table->date('expense_date');
            $table->string('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
            $table->date('reviewed_at')->nullable();
            $table->string('rejection_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_claims');
    }
};

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
        Schema::table('employees', function (Blueprint $table) {
            $table->decimal('Salary', 10, 2)->change();
            // $table->foreignId('department_id')
            //     ->nullable()
            //     ->constrained('departments')
            //     ->nullOnDelete()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {

            $table->decimal('salary', 10, 2)->change();
            // $table->foreignId('department_id')
            //     ->nullable()
            //     ->constrained('departments')
            //     ->change()
            //     ->nullOnDelete();
        });
    }
};

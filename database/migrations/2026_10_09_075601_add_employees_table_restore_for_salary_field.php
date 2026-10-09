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

        schema::table('employees', function (Blueprint $table) {
            $table->decimal('salary', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        schema::table('employees', function (Blueprint $table) {
            $table->decimal('Salary', 10, 2)->change();
        });
    }
};

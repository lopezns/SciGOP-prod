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
            // Agregar columna status
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active')->after('active');
            
            // Agregar otros campos faltantes
            $table->enum('gender', ['M', 'F', 'O'])->after('birth_date');
            $table->string('emergency_contact')->nullable()->after('account_number');
            
            // Hacer email nullable
            $table->string('email')->nullable()->change();
            
            // Índice para status
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['status', 'gender', 'emergency_contact']);
            $table->dropIndex(['employees_status_index']);
        });
    }
};

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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('document_type')->default('CC'); // CC, TI, CE, PAS
            $table->string('document_number')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date');
            $table->date('hire_date');
            $table->date('termination_date')->nullable();
            $table->string('position');
            $table->string('department');
            $table->decimal('base_salary', 15, 2); // Salario base en COP
            $table->enum('contract_type', ['indefinido', 'fijo', 'obra_labor', 'aprendizaje']);
            $table->enum('salary_type', ['fijo', 'variable', 'mixto']);
            $table->boolean('active')->default(true);
            
            // Campos para DIAN y seguridad social
            $table->string('eps')->nullable(); // EPS afiliada
            $table->string('pension_fund')->nullable(); // Fondo de pensiones
            $table->string('arl')->nullable(); // ARL
            $table->string('cesantias_fund')->nullable(); // Fondo cesantías
            $table->boolean('high_risk')->default(false); // Trabajo de alto riesgo
            $table->boolean('integral_salary')->default(false); // Salario integral
            $table->decimal('transportation_allowance', 10, 2)->default(0); // Auxilio transporte
            
            // Información bancaria
            $table->string('bank')->nullable();
            $table->string('account_type')->nullable(); // ahorro, corriente
            $table->string('account_number')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['active']);
            $table->index(['department']);
            $table->index(['contract_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

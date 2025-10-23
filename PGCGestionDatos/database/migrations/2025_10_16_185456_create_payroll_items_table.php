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
        Schema::create('payroll_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->string('concept_code'); // Código del concepto (DIAN)
            $table->string('concept_name'); // Nombre del concepto
            $table->enum('type', ['income', 'deduction', 'employer_contribution', 'provision']); // Tipo de concepto
            $table->enum('calculation_type', ['fixed', 'percentage', 'variable']); // Tipo de cálculo
            $table->decimal('base_amount', 15, 2)->nullable(); // Monto base para cálculo
            $table->decimal('percentage', 8, 4)->nullable(); // Porcentaje si aplica
            $table->decimal('amount', 15, 2); // Valor calculado
            $table->boolean('taxable')->default(false); // Si es gravable
            $table->boolean('affects_social_security')->default(false); // Si afecta seguridad social
            $table->text('description')->nullable(); // Descripción adicional
            $table->json('metadata')->nullable(); // Datos adicionales en JSON
            
            $table->timestamps();
            
            // Índices
            $table->index(['payroll_id']);
            $table->index(['concept_code']);
            $table->index(['type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_items');
    }
};

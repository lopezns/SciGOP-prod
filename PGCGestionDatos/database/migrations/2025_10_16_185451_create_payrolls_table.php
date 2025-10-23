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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('payroll_code')->unique();
            $table->date('period_start'); // Inicio período nómina
            $table->date('period_end');   // Fin período nómina
            $table->date('payment_date'); // Fecha de pago
            $table->enum('payroll_type', ['mensual', 'quincenal', 'semanal', 'extraordinaria']);
            $table->integer('days_worked')->default(30);
            $table->decimal('hours_worked', 8, 2)->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            
            // Ingresos
            $table->decimal('base_salary', 15, 2); // Salario base
            $table->decimal('overtime_pay', 15, 2)->default(0); // Horas extra
            $table->decimal('bonuses', 15, 2)->default(0); // Bonificaciones
            $table->decimal('commissions', 15, 2)->default(0); // Comisiones
            $table->decimal('transportation_allowance', 15, 2)->default(0); // Auxilio transporte
            $table->decimal('food_allowance', 15, 2)->default(0); // Auxilio alimentación
            $table->decimal('other_income', 15, 2)->default(0); // Otros ingresos
            $table->decimal('total_income', 15, 2); // Total ingresos
            
            // Deducciones obligatorias (DIAN)
            $table->decimal('health_contribution', 15, 2)->default(0); // Salud (4%)
            $table->decimal('pension_contribution', 15, 2)->default(0); // Pensión (4%)
            $table->decimal('solidarity_fund', 15, 2)->default(0); // Fondo solidaridad (1%)
            $table->decimal('unemployment_fund', 15, 2)->default(0); // Fondo desempleo
            
            // Retenciones
            $table->decimal('income_tax_withholding', 15, 2)->default(0); // Retención fuente
            $table->decimal('other_withholdings', 15, 2)->default(0); // Otras retenciones
            
            // Otras deducciones
            $table->decimal('loan_deductions', 15, 2)->default(0); // Descuentos préstamos
            $table->decimal('other_deductions', 15, 2)->default(0); // Otras deducciones
            $table->decimal('total_deductions', 15, 2); // Total deducciones
            
            // Aportes patronales (DIAN)
            $table->decimal('employer_health', 15, 2)->default(0); // Salud patronal (8.5%)
            $table->decimal('employer_pension', 15, 2)->default(0); // Pensión patronal (12%)
            $table->decimal('employer_arl', 15, 2)->default(0); // ARL (0.348% - 6.96%)
            $table->decimal('employer_sena', 15, 2)->default(0); // SENA (2%)
            $table->decimal('employer_icbf', 15, 2)->default(0); // ICBF (3%)
            $table->decimal('employer_compensation', 15, 2)->default(0); // Caja compensación (4%)
            $table->decimal('total_employer_contributions', 15, 2); // Total aportes patronales
            
            // Provisiones
            $table->decimal('vacation_provision', 15, 2)->default(0); // Provisión vacaciones
            $table->decimal('bonus_provision', 15, 2)->default(0); // Provisión prima
            $table->decimal('severance_provision', 15, 2)->default(0); // Provisión cesantías
            $table->decimal('severance_interest', 15, 2)->default(0); // Intereses cesantías
            
            // Totales finales
            $table->decimal('net_salary', 15, 2); // Salario neto a pagar
            $table->decimal('total_cost', 15, 2); // Costo total para el empleador
            
            $table->enum('status', ['draft', 'calculated', 'approved', 'paid'])->default('draft');
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Índices
            $table->index(['employee_id']);
            $table->index(['period_start', 'period_end']);
            $table->index(['payment_date']);
            $table->index(['payroll_type']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};

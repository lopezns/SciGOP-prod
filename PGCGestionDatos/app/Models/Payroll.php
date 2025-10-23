<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Payroll extends Model
{
    protected $fillable = [
        'employee_id',
        'payroll_code',
        'period_start',
        'period_end',
        'payment_date',
        'payroll_type',
        'days_worked',
        'hours_worked',
        'overtime_hours',
        'base_salary',
        'overtime_pay',
        'bonuses',
        'commissions',
        'transportation_allowance',
        'food_allowance',
        'other_income',
        'total_income',
        'health_contribution',
        'pension_contribution',
        'solidarity_fund',
        'unemployment_fund',
        'income_tax_withholding',
        'other_withholdings',
        'loan_deductions',
        'other_deductions',
        'total_deductions',
        'employer_health',
        'employer_pension',
        'employer_arl',
        'employer_sena',
        'employer_icbf',
        'employer_compensation',
        'total_employer_contributions',
        'vacation_provision',
        'bonus_provision',
        'severance_provision',
        'severance_interest',
        'net_salary',
        'total_cost',
        'status',
        'notes',
    ];

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'payment_date' => 'date',
        'days_worked' => 'integer',
        'hours_worked' => 'decimal:2',
        'overtime_hours' => 'decimal:2',
        'base_salary' => 'decimal:2',
        'overtime_pay' => 'decimal:2',
        'bonuses' => 'decimal:2',
        'commissions' => 'decimal:2',
        'transportation_allowance' => 'decimal:2',
        'food_allowance' => 'decimal:2',
        'other_income' => 'decimal:2',
        'total_income' => 'decimal:2',
        'health_contribution' => 'decimal:2',
        'pension_contribution' => 'decimal:2',
        'solidarity_fund' => 'decimal:2',
        'unemployment_fund' => 'decimal:2',
        'income_tax_withholding' => 'decimal:2',
        'other_withholdings' => 'decimal:2',
        'loan_deductions' => 'decimal:2',
        'other_deductions' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'employer_health' => 'decimal:2',
        'employer_pension' => 'decimal:2',
        'employer_arl' => 'decimal:2',
        'employer_sena' => 'decimal:2',
        'employer_icbf' => 'decimal:2',
        'employer_compensation' => 'decimal:2',
        'total_employer_contributions' => 'decimal:2',
        'vacation_provision' => 'decimal:2',
        'bonus_provision' => 'decimal:2',
        'severance_provision' => 'decimal:2',
        'severance_interest' => 'decimal:2',
        'net_salary' => 'decimal:2',
        'total_cost' => 'decimal:2',
    ];

    // Relaciones
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    public function incomeItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class)->where('type', 'income');
    }

    public function deductionItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class)->where('type', 'deduction');
    }

    public function employerContributionItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class)->where('type', 'employer_contribution');
    }

    public function provisionItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class)->where('type', 'provision');
    }

    // Accessors
    public function getFormattedTotalIncomeAttribute(): string
    {
        return number_format($this->total_income, 0, ',', '.');
    }

    public function getFormattedTotalDeductionsAttribute(): string
    {
        return number_format($this->total_deductions, 0, ',', '.');
    }

    public function getFormattedNetSalaryAttribute(): string
    {
        return number_format($this->net_salary, 0, ',', '.');
    }

    public function getFormattedTotalCostAttribute(): string
    {
        return number_format($this->total_cost, 0, ',', '.');
    }

    public function getPeriodDescriptionAttribute(): string
    {
        return $this->period_start->format('d/m/Y') . ' - ' . $this->period_end->format('d/m/Y');
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'calculated' => 'info',
            'approved' => 'warning',
            'paid' => 'success',
            default => 'secondary'
        };
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Borrador',
            'calculated' => 'Calculado',
            'approved' => 'Aprobado',
            'paid' => 'Pagado',
            default => 'Desconocido'
        };
    }

    // Scopes
    public function scopeByPeriod($query, $startDate, $endDate)
    {
        return $query->whereBetween('period_start', [$startDate, $endDate]);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPayrollType($query, $type)
    {
        return $query->where('payroll_type', $type);
    }

    public function scopeCurrentMonth($query)
    {
        $now = Carbon::now();
        return $query->whereYear('period_start', $now->year)
                    ->whereMonth('period_start', $now->month);
    }

    // Métodos de cálculo
    public function calculatePayroll(): void
    {
        $employee = $this->employee;
        
        // Calcular ingresos
        $this->base_salary = $employee->base_salary;
        $this->transportation_allowance = $employee->getTransportationAllowanceAmount();
        
        // Calcular horas extra (25%, 75%, 100% según legislación)
        $this->overtime_pay = $this->calculateOvertimePay();
        
        $this->total_income = $this->base_salary + 
                             $this->overtime_pay + 
                             $this->bonuses + 
                             $this->commissions + 
                             $this->transportation_allowance + 
                             $this->food_allowance + 
                             $this->other_income;

        // Calcular deducciones del empleado
        $this->health_contribution = $employee->getHealthContributionEmployee();
        $this->pension_contribution = $employee->getPensionContributionEmployee();
        $this->solidarity_fund = $employee->getSolidarityFundContribution();
        
        // Calcular retención en la fuente
        $this->income_tax_withholding = $this->calculateIncomeTaxWithholding();
        
        $this->total_deductions = $this->health_contribution + 
                                 $this->pension_contribution + 
                                 $this->solidarity_fund + 
                                 $this->unemployment_fund + 
                                 $this->income_tax_withholding + 
                                 $this->other_withholdings + 
                                 $this->loan_deductions + 
                                 $this->other_deductions;

        // Calcular aportes patronales
        $this->employer_health = $employee->getHealthContributionEmployer();
        $this->employer_pension = $employee->getPensionContributionEmployer();
        $this->employer_arl = $employee->getArlContribution();
        $this->employer_sena = $employee->getSenaContribution();
        $this->employer_icbf = $employee->getIcbfContribution();
        $this->employer_compensation = $employee->getCompensationFundContribution();
        
        $this->total_employer_contributions = $this->employer_health + 
                                            $this->employer_pension + 
                                            $this->employer_arl + 
                                            $this->employer_sena + 
                                            $this->employer_icbf + 
                                            $this->employer_compensation;

        // Calcular provisiones
        $this->vacation_provision = $employee->getVacationProvision();
        $this->bonus_provision = $employee->getBonusProvision();
        $this->severance_provision = $employee->getSeveranceProvision();
        $this->severance_interest = $employee->getSeveranceInterest();

        // Calcular totales finales
        $this->net_salary = $this->total_income - $this->total_deductions;
        $this->total_cost = $this->total_income + 
                           $this->total_employer_contributions + 
                           $this->vacation_provision + 
                           $this->bonus_provision + 
                           $this->severance_provision + 
                           $this->severance_interest;

        $this->status = 'calculated';
        $this->save();
        
        // Generar items detallados
        $this->generatePayrollItems();
    }

    private function calculateOvertimePay(): float
    {
        if ($this->overtime_hours <= 0) {
            return 0;
        }

        $hourlyRate = $this->employee->base_salary / 240; // 30 días * 8 horas
        
        // Horas extra diurnas (25%)
        $diurnalOvertimeRate = $hourlyRate * 1.25;
        
        return $this->overtime_hours * $diurnalOvertimeRate;
    }

    private function calculateIncomeTaxWithholding(): float
    {
        if (!$this->employee->isSubjectToRetentionAtSource()) {
            return 0;
        }

        // Tabla de retención en la fuente simplificada
        // En implementación real se usarían las tablas oficiales de la DIAN
        $monthlyIncome = $this->base_salary;
        
        if ($monthlyIncome <= 2000000) return 0;
        if ($monthlyIncome <= 3000000) return $monthlyIncome * 0.02;
        if ($monthlyIncome <= 5000000) return $monthlyIncome * 0.04;
        
        return $monthlyIncome * 0.06;
    }

    private function generatePayrollItems(): void
    {
        // Limpiar items existentes
        $this->items()->delete();

        $items = [];

        // Items de ingresos
        if ($this->base_salary > 0) {
            $items[] = new PayrollItem([
                'concept_code' => 'ING001',
                'concept_name' => 'Salario Básico',
                'type' => 'income',
                'calculation_type' => 'fixed',
                'amount' => $this->base_salary,
                'taxable' => true,
                'affects_social_security' => true,
            ]);
        }

        if ($this->overtime_pay > 0) {
            $items[] = new PayrollItem([
                'concept_code' => 'ING002',
                'concept_name' => 'Horas Extra',
                'type' => 'income',
                'calculation_type' => 'variable',
                'amount' => $this->overtime_pay,
                'taxable' => true,
                'affects_social_security' => true,
            ]);
        }

        if ($this->transportation_allowance > 0) {
            $items[] = new PayrollItem([
                'concept_code' => 'ING003',
                'concept_name' => 'Auxilio de Transporte',
                'type' => 'income',
                'calculation_type' => 'fixed',
                'amount' => $this->transportation_allowance,
                'taxable' => false,
                'affects_social_security' => false,
            ]);
        }

        // Items de deducciones
        if ($this->health_contribution > 0) {
            $items[] = new PayrollItem([
                'concept_code' => 'DED001',
                'concept_name' => 'Salud Empleado (4%)',
                'type' => 'deduction',
                'calculation_type' => 'percentage',
                'base_amount' => $this->employee->getSalaryForSocialSecurity(),
                'percentage' => 4.0,
                'amount' => $this->health_contribution,
            ]);
        }

        if ($this->pension_contribution > 0) {
            $items[] = new PayrollItem([
                'concept_code' => 'DED002',
                'concept_name' => 'Pensión Empleado (4%)',
                'type' => 'deduction',
                'calculation_type' => 'percentage',
                'base_amount' => $this->employee->getSalaryForSocialSecurity(),
                'percentage' => 4.0,
                'amount' => $this->pension_contribution,
            ]);
        }

        // Aportes patronales
        if ($this->employer_health > 0) {
            $items[] = new PayrollItem([
                'concept_code' => 'PAT001',
                'concept_name' => 'Salud Patronal (8.5%)',
                'type' => 'employer_contribution',
                'calculation_type' => 'percentage',
                'base_amount' => $this->employee->getSalaryForSocialSecurity(),
                'percentage' => 8.5,
                'amount' => $this->employer_health,
            ]);
        }

        // Provisiones
        if ($this->vacation_provision > 0) {
            $items[] = new PayrollItem([
                'concept_code' => 'PROV001',
                'concept_name' => 'Provisión Vacaciones',
                'type' => 'provision',
                'calculation_type' => 'percentage',
                'base_amount' => $this->base_salary,
                'percentage' => 4.17,
                'amount' => $this->vacation_provision,
            ]);
        }

        // Guardar todos los items
        $this->items()->saveMany($items);
    }
}
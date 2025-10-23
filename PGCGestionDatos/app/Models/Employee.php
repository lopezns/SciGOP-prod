<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Employee extends Model
{
    protected $fillable = [
        'employee_code',
        'first_name',
        'last_name',
        'document_type',
        'document_number',
        'email',
        'phone',
        'address',
        'birth_date',
        'gender',
        'hire_date',
        'termination_date',
        'position',
        'department',
        'base_salary',
        'contract_type',
        'salary_type',
        'active',
        'status',
        'eps',
        'pension_fund',
        'arl',
        'cesantias_fund',
        'high_risk',
        'integral_salary',
        'transportation_allowance',
        'bank',
        'account_type',
        'account_number',
        'emergency_contact',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'base_salary' => 'decimal:2',
        'transportation_allowance' => 'decimal:2',
        'active' => 'boolean',
        'high_risk' => 'boolean',
        'integral_salary' => 'boolean',
    ];

    // Relaciones
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class);
    }

    public function currentPayrolls(): HasMany
    {
        return $this->hasMany(Payroll::class)->where('status', '!=', 'paid');
    }

    // Accessors
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getFormattedSalaryAttribute(): string
    {
        return number_format($this->base_salary, 0, ',', '.');
    }

    public function getAgeAttribute(): int
    {
        return $this->birth_date->age;
    }

    public function getYearsOfServiceAttribute(): int
    {
        $endDate = $this->termination_date ?? Carbon::now();
        return $this->hire_date->diffInYears($endDate);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByDepartment($query, $department)
    {
        return $query->where('department', $department);
    }

    public function scopeByContractType($query, $contractType)
    {
        return $query->where('contract_type', $contractType);
    }

    // Métodos para cálculos DIAN
    public function isSubjectToTransportationAllowance(): bool
    {
        $minimumWage = 1300000; // SMLV 2024
        return $this->base_salary <= 2 * $minimumWage;
    }

    public function getTransportationAllowanceAmount(): float
    {
        if (!$this->isSubjectToTransportationAllowance()) {
            return 0;
        }
        
        return $this->transportation_allowance > 0 
            ? $this->transportation_allowance 
            : 140606; // Auxilio de transporte 2024
    }

    public function isSubjectToRetentionAtSource(): bool
    {
        $exemptLimit = 4915000; // UVT 2024 * 95
        return $this->base_salary >= $exemptLimit;
    }

    public function getSalaryForSocialSecurity(): float
    {
        // Para salario integral, la base es el 70% del salario
        if ($this->integral_salary) {
            return $this->base_salary * 0.70;
        }
        
        return $this->base_salary;
    }

    public function getHealthContributionEmployee(): float
    {
        return $this->getSalaryForSocialSecurity() * 0.04; // 4%
    }

    public function getPensionContributionEmployee(): float
    {
        return $this->getSalaryForSocialSecurity() * 0.04; // 4%
    }

    public function getSolidarityFundContribution(): float
    {
        $minimumWage = 1300000;
        if ($this->base_salary >= 4 * $minimumWage) {
            return $this->getSalaryForSocialSecurity() * 0.01; // 1%
        }
        return 0;
    }

    // Aportes patronales
    public function getHealthContributionEmployer(): float
    {
        return $this->getSalaryForSocialSecurity() * 0.085; // 8.5%
    }

    public function getPensionContributionEmployer(): float
    {
        return $this->getSalaryForSocialSecurity() * 0.12; // 12%
    }

    public function getArlContribution(): float
    {
        // ARL varía según el riesgo, por defecto clase I (0.348%)
        $rate = $this->high_risk ? 0.0696 : 0.00348; // Clase V vs Clase I
        return $this->getSalaryForSocialSecurity() * $rate;
    }

    public function getSenaContribution(): float
    {
        return $this->getSalaryForSocialSecurity() * 0.02; // 2%
    }

    public function getIcbfContribution(): float
    {
        return $this->getSalaryForSocialSecurity() * 0.03; // 3%
    }

    public function getCompensationFundContribution(): float
    {
        return $this->getSalaryForSocialSecurity() * 0.04; // 4%
    }

    // Provisiones
    public function getVacationProvision(): float
    {
        return ($this->base_salary / 12) * 0.5; // 15 días por año = 12.5 días por mes
    }

    public function getBonusProvision(): float
    {
        return $this->base_salary / 12; // Una mensualidad al año
    }

    public function getSeveranceProvision(): float
    {
        return $this->base_salary / 12; // Una mensualidad por año
    }

    public function getSeveranceInterest(): float
    {
        return $this->getSeveranceProvision() * 0.12; // 12% anual sobre cesantías
    }
}
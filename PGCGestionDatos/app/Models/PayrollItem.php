<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollItem extends Model
{
    protected $fillable = [
        'payroll_id',
        'concept_code',
        'concept_name',
        'type',
        'calculation_type',
        'base_amount',
        'percentage',
        'amount',
        'taxable',
        'affects_social_security',
        'description',
        'metadata',
    ];

    protected $casts = [
        'base_amount' => 'decimal:2',
        'percentage' => 'decimal:4',
        'amount' => 'decimal:2',
        'taxable' => 'boolean',
        'affects_social_security' => 'boolean',
        'metadata' => 'json',
    ];

    // Relaciones
    public function payroll(): BelongsTo
    {
        return $this->belongsTo(Payroll::class);
    }

    // Accessors
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', '.');
    }

    public function getFormattedBaseAmountAttribute(): string
    {
        return number_format($this->base_amount ?? 0, 0, ',', '.');
    }

    public function getFormattedPercentageAttribute(): string
    {
        return number_format($this->percentage ?? 0, 2, ',', '.') . '%';
    }

    public function getTypeTextAttribute(): string
    {
        return match($this->type) {
            'income' => 'Ingreso',
            'deduction' => 'Deducción',
            'employer_contribution' => 'Aporte Patronal',
            'provision' => 'Provisión',
            default => 'Desconocido'
        };
    }

    public function getCalculationTypeTextAttribute(): string
    {
        return match($this->calculation_type) {
            'fixed' => 'Valor Fijo',
            'percentage' => 'Porcentaje',
            'variable' => 'Variable',
            default => 'Desconocido'
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'income' => 'success',
            'deduction' => 'danger',
            'employer_contribution' => 'warning',
            'provision' => 'info',
            default => 'secondary'
        };
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeTaxable($query)
    {
        return $query->where('taxable', true);
    }

    public function scopeAffectsSocialSecurity($query)
    {
        return $query->where('affects_social_security', true);
    }

    public function scopeByConceptCode($query, $conceptCode)
    {
        return $query->where('concept_code', $conceptCode);
    }

    // Métodos estáticos para obtener códigos de conceptos DIAN
    public static function getIncomeConceptCodes(): array
    {
        return [
            'ING001' => 'Salario Básico',
            'ING002' => 'Horas Extra Diurnas',
            'ING003' => 'Auxilio de Transporte',
            'ING004' => 'Horas Extra Nocturnas',
            'ING005' => 'Horas Extra Dominicales y Festivos',
            'ING006' => 'Recargo Nocturno',
            'ING007' => 'Recargo Dominical y Festivo',
            'ING008' => 'Comisiones',
            'ING009' => 'Bonificaciones',
            'ING010' => 'Auxilio de Alimentación',
            'ING011' => 'Prima de Servicios',
            'ING012' => 'Vacaciones',
            'ING013' => 'Cesantías',
            'ING014' => 'Intereses de Cesantías',
            'ING015' => 'Indemnización por Despido',
        ];
    }

    public static function getDeductionConceptCodes(): array
    {
        return [
            'DED001' => 'Salud Empleado (4%)',
            'DED002' => 'Pensión Empleado (4%)',
            'DED003' => 'Fondo de Solidaridad (1%)',
            'DED004' => 'Fondo de Desempleo',
            'DED005' => 'Retención en la Fuente',
            'DED006' => 'Préstamos',
            'DED007' => 'Embargos Judiciales',
            'DED008' => 'Cuotas Sindicales',
            'DED009' => 'Cooperativas',
            'DED010' => 'Otros Descuentos',
        ];
    }

    public static function getEmployerContributionConceptCodes(): array
    {
        return [
            'PAT001' => 'Salud Patronal (8.5%)',
            'PAT002' => 'Pensión Patronal (12%)',
            'PAT003' => 'ARL (0.348% - 6.96%)',
            'PAT004' => 'SENA (2%)',
            'PAT005' => 'ICBF (3%)',
            'PAT006' => 'Caja de Compensación (4%)',
        ];
    }

    public static function getProvisionConceptCodes(): array
    {
        return [
            'PROV001' => 'Provisión Vacaciones',
            'PROV002' => 'Provisión Prima de Servicios',
            'PROV003' => 'Provisión Cesantías',
            'PROV004' => 'Provisión Intereses de Cesantías',
        ];
    }

    public static function getAllConceptCodes(): array
    {
        return array_merge(
            self::getIncomeConceptCodes(),
            self::getDeductionConceptCodes(),
            self::getEmployerContributionConceptCodes(),
            self::getProvisionConceptCodes()
        );
    }

    // Método para obtener la descripción de un código de concepto
    public static function getConceptDescription(string $conceptCode): ?string
    {
        return self::getAllConceptCodes()[$conceptCode] ?? null;
    }
}